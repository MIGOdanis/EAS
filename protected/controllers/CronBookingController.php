<?php

class CronBookingController extends Controller
{
	public function actionCronCountBooking()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.start_time <= '" . date("Y-m-d H:i:s", strtotime(date("Y-m-d") . ' +16 day')) . "'");
		$criteria->addCondition("t.end_time >= '" . date("Y-m-d H:i:s") . "'");
		$criteria->addCondition("t.status = 1");
		$campaign = TosCoreCampaign::model()->with("budget","strategy","strategy.strategyBudget","strategy.strategyTotalHit")->findAll($criteria);
		foreach ($campaign as $value) {
			
			//計算訂單booking
			$campaignBudgetQuota = ceil($value->budget->total_budget / 100);

			//計算扣除已設定策略後剩下的BOOKING
			$strategyBudget = 0;
			$unsetBudget = 0;

			foreach ($value->strategy as $strategy) {
				
				$budget = ceil($strategy->strategyBudget->total_budget / 100);
				if($budget == 0){
					$unsetBudget++;
				}
				$strategyBudget = $strategyBudget + $budget;
			}

			$campaignBudgetQuota = $campaignBudgetQuota - $strategyBudget;
			if($unsetBudget < 1)
				$unsetBudget = 1;

			foreach($value->strategy as $strategy){
				$bookingDay = strtotime($strategy->end_time) -  strtotime($strategy->start_time);
				$bookingDay = ceil($bookingDay / 86400);
				$remainingDay = strtotime($strategy->end_time) - time();
				$remainingDay = ceil($remainingDay / 86400);	

				$criteria = new CDbCriteria;
				$criteria->addCondition("t.strategy_id = '" . $strategy->id . "'");
				$booking = CampaignBooking::model()->find($criteria);
				if($booking === null)
					$booking = new CampaignBooking();

				$booking->campaign_id = $value->id;
				$booking->strategy_id = $strategy->id;
				$booking->type = $strategy->medium;
				//預定天數與剩餘天數
				$booking->booking_day = $bookingDay;
				$booking->remaining_day = $remainingDay;	
				$booking = $this->countBudger($booking,$strategy,$campaignBudgetQuota,$unsetBudget);
				$booking = $this->countClick($booking,$strategy);
				$booking = $this->countImp($booking,$strategy);
				$booking->start_time = strtotime($strategy->start_time);
				$booking->end_time = strtotime($strategy->end_time);
				$booking->sync_time = time();				
			}

			if(!$booking->save()){
				// print_r($booking); exit;
			}

		}

		$this->clearRemainingDay();

	}

	//計算預算
	public function countBudger($booking,$value,$campaignBudgetQuota,$unsetBudget){

		$budget = ceil($value->strategyBudget->total_budget / 100);
		if($budget == 0){
			$budget = ceil($campaignBudgetQuota / $unsetBudget);
		}		

		$booking->booking_budget = $budget;
		$booking->remaining_budget = ceil($booking->booking_budget - ($value->strategyTotalHit->cost / 100));
		$booking->day_budget = ceil(($booking->remaining_day == 0) ? 0 : ($booking->remaining_budget / $booking->remaining_day));
		if($value->strategyBudget->max_daily_budget > 0){
			$booking->day_budget = ceil($value->strategyBudget->max_daily_budget / 100);
		}

		return  $booking;
	}



	//計算點擊
	public function countClick($booking,$value){

		$status = 1; // 1=實際值 2=預估值
		//如果未填寫click booking 用5cpc算
		$click = $value->strategyBudget->total_click;
		if($click == 0){
			$status = 2;
			$click = $this->transClick($booking->booking_budget);
		}

		//總計click
		$booking->booking_click = $click;
		
		//如果未填寫click booking
		$remainingClick = ceil($booking->booking_click - $value->strategyTotalHit->click);
		if($status == 2){
			$remainingClick = ceil($booking->remaining_budget / 5);
		}

		if($remainingClick < 0){
			$remainingClick = 0;
		}

		$booking->remaining_click = $remainingClick;

		//計算click
		$dayClick = ceil(($booking->remaining_day == 0) ? 0 : ($booking->remaining_click / $booking->remaining_day));
		if($value->strategyBudget->max_daily_click > 0){
			$dayClick = $value->strategyBudget->max_daily_click;
		}

		//如果有設置每日預算上限
		if($value->strategyBudget->max_daily_budget > 0){
			$status = 2;
			$budgetDayClick = ceil( $value->strategyBudget->max_daily_budget / 5 );

			//CPC5元每日預算上限回推低於日點擊上限應以預算上限為準
			if($budgetDayClick < $dayClick){
				$dayClick = $budgetDayClick;
			}
		}

		if($dayClick < 0){
			$dayClick = 0;
		}

		$booking->day_click = $dayClick;
		$booking->click_status = $status;
		return  $booking;
	}


	//計算曝光
	public function countImp($booking,$value){

		$status = 1;
		//如果位置設定總IMP以CTR回推
		$imp = $value->strategyBudget->total_imp;
		if($imp == 0){
			$status = 2;
			$imp = $this->transImp($booking->booking_click,$value->strategyTotalHit);
		}

		$booking->booking_imp = $imp;

		if($status == 1){

			$booking->remaining_imp = $booking->booking_imp - $value->strategyTotalHit->impression;
			$booking->day_imp = ceil(($booking->remaining_day == 0) ? 0 : ($booking->remaining_imp / $booking->remaining_day));

		}else{

			if($booking->remaining_click == 0 && $value->strategyBudget->total_imp == 0){
				//結束的訂單將數字歸零
				$booking->remaining_imp = 0;
				$booking->day_imp = 0;
			}else{
				if($booking->day_click >= $booking->remaining_click){
					//預算還沒花完但是click超過預計
					$booking->remaining_imp = $this->transImp($booking->day_click,$value->strategyTotalHit);
				}else{
					$booking->remaining_imp = $this->transImp($booking->remaining_click,$value->strategyTotalHit);
				}

				$booking->day_imp = $this->transImp($booking->day_click,$value->strategyTotalHit);	
			}

		}
		if($value->strategyBudget->max_daily_imp > 0){
			$booking->day_imp = $value->strategyBudget->max_daily_imp;
		}

		$booking->imp_status = $status;

		return  $booking;
	}


	public function transClick($budget){
		return  ceil($budget / 5);
	}

	public function transImp($click,$strategyTotalHit){
		if($strategyTotalHit->impression > 0){
			$ctr = (($strategyTotalHit->click / $strategyTotalHit->impression) * 100);
		}else{
			$ctr = 0.1;
		}
		
		if($ctr <= 0)
			$ctr = 0.1;

		return ceil(($click / $ctr)  * 100);
	}

	public function clearRemainingDay(){
		$criteria = new CDbCriteria;
		$criteria->addCondition("end_time < '" . time() ."'");
		CampaignBooking::model()->updateAll(array(
			'remaining_day' => 0,
			'remaining_click' => 0,
			'day_click' => 0,
			'remaining_imp' => 0,
			'day_imp' => 0,
			'remaining_budget' => 0,
			'day_budget' => 0,
			'sync_time' => time(),
		),$criteria);
	}

	public function actionCronCountBookingHistory()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$criteria = new CDbCriteria;
		$criteria->addCondition("remaining_day > 0");
		$model = CampaignBooking::model()->findAll($criteria);
		foreach ($model as $value) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("strategy_id = '" . $value->strategy_id . "'");
			$criteria->addCondition("date = '" . date("Y-m-d 00:00:00", $value->sync_time) . "'");
			$run = TosCoreStrategyDailyHit::model()->find($criteria);

			$criteria = new CDbCriteria;
			$criteria->addCondition("strategy_id = '" . $value->strategy_id . "'");
			$criteria->addCondition("history_time = '" . strtotime(date("Y-m-d 00:00:00", $value->sync_time)) . "'");
			$history = CampaignBookingHistory::model()->find($criteria);	
			
			if($history === null)		
				$history = new CampaignBookingHistory();
			
			$history->campaign_id = $value->campaign_id;
			$history->strategy_id = $value->strategy_id;
			$history->booking_day = $value->booking_day;
			$history->remaining_day = $value->remaining_day;
			$history->booking_click = $value->booking_click;
			$history->remaining_click = $value->remaining_click;
			$history->day_click = $value->day_click;
			$history->click_status = $value->click_status;
			$history->booking_imp = $value->booking_imp;
			$history->remaining_imp = $value->remaining_imp;
			$history->day_imp = $value->day_imp;
			$history->imp_status = $value->imp_status;
			$history->booking_budget = $value->booking_budget;
			$history->remaining_budget = $value->remaining_budget;
			$history->day_budget = $value->day_budget;
			$history->history_time = strtotime(date("Y-m-d 00:00:00", $value->sync_time));

			if($run === null){
				$history->run_click = 0;
				$history->run_imp = 0;
				$history->run_budget = 0;
			}else{
				$history->run_click = $run->click;
				$history->run_imp = $run->impression;
				$history->run_budget = ceil($run->cost / 100);
			}

			if(!$history->save()){
				// print_r($history); exit;
			}
		}

	}

}
