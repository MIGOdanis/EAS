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
		$onTimeCampaign = TosCoreCampaign::model()->with("budget","totalHit")->findAll($criteria);
		foreach ($onTimeCampaign as $value) {
			
			$bookingDay = strtotime($value->end_time) -  strtotime($value->start_time);
			$bookingDay = ceil($bookingDay / 86400);

			$remainingDay = strtotime($value->end_time) - time();
			$remainingDay = ceil($remainingDay / 86400);

			$criteria = new CDbCriteria;
			$criteria->addCondition("t.campaign_id = '" . $value->id . "'");
			$booking = CampaignBooking::model()->find($criteria);
			
			if($booking === null)
				$booking = new CampaignBooking();

			$booking->campaign_id = $value->id;

			//預定天數與剩餘天數
			$booking->booking_day = $bookingDay;
			$booking->remaining_day = $remainingDay;

			$booking = $this->countBudger($booking,$value);
			$booking = $this->countClick($booking,$value);
			$booking = $this->countImp($booking,$value);

			$booking->start_time = strtotime($value->start_time);
			$booking->end_time = strtotime($value->end_time);
			$booking->sync_time = time();


			if(!$booking->save()){
				// print_r($booking); exit;
			}

		}

		$this->clearRemainingDay();

	}

	//計算預算
	public function countBudger($booking,$value){

		$booking->booking_budget = ceil($value->budget->total_budget / 100);
		$booking->remaining_budget = ceil(($value->budget->total_budget / 100) - ($value->totalHit->total_hit_budget / 100));
		$booking->day_budget = ceil(($booking->remaining_day == 0) ? 0 : ($booking->remaining_budget / $booking->remaining_day));

		return  $booking;
	}



	//計算點擊
	public function countClick($booking,$value){

		$status = 1; // 1=實際值 2=預估值
		//如果未填寫click bookin
		$bookingClick = $value->budget->total_click;
		if($bookingClick == 0){
			$status = 2;
			$bookingClick = $this->transClick($value->budget->total_budget / 100);
		}

		//總計click
		$booking->booking_click = $bookingClick;
		
		//如果未填寫click bookin
		$remainingClick = ceil($bookingClick - $value->totalHit->total_hit_click);
		if($value->budget->total_click == 0){
			$remainingClick = ceil((($value->budget->total_budget / 100) - ($value->totalHit->total_hit_budget / 100)) / 5);
		}

		if($remainingClick < 0){
			$remainingClick = 0;
		}

		$booking->remaining_click = $remainingClick;

		//計算click
		$dayClick =  ceil(($booking->remaining_day == 0) ? 0 : ($booking->remaining_click / $booking->remaining_day));
		if($value->budget->max_daily_click > 0){
			$dayClick = $value->budget->max_daily_click;
		}

		//如果有設置每日預算上限
		if($value->budget->max_daily_budget > 0){
			$status = 2;
			$budgetDayClick = ceil( $value->budget->max_daily_budget / 5 );

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
		$bookingImp = $value->budget->total_pv;
		if($bookingImp == 0){
			$status = 2;
			if($booking->day_click >= $booking->remaining_click){
				//預算還沒花完但是click超過預計
				$bookingImp = $this->transImp($booking->day_click);
			}else{
				$bookingImp = $this->transImp($booking->remaining_click);
			}
		}


		$dayImp = $bookingImp = $this->transImp($booking->day_click);


		$booking->booking_imp = $bookingImp;

		if($booking->remaining_click == 0 && $value->budget->total_pv == 0){
			//結束的訂單將數字歸零
			$booking->remaining_imp = 0;
			$booking->day_imp = 0;
		}else{
			if($booking->day_click >= $booking->remaining_click){
				$booking->remaining_imp = ceil($bookingImp);
				
			}else{
				$booking->remaining_imp = ceil($bookingImp - $value->totalHit->total_hit_pv);				
			}

			$booking->day_imp = $dayImp;	
		
		}

		$booking->imp_status = $status;

		return  $booking;
	}


	public function transClick($budget){
		return  ceil($budget / 5);
	}

	public function transImp($click){
		$ctr = 0.1;
		return (($click / $ctr)  * 100);
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
			$criteria->addCondition("campaign_id = '" . $value->campaign_id . "'");
			$criteria->addCondition("date = '" . date("Y-m-d 00:00:00", $value->sync_time) . "'");
			$run = TosCoreCampaignDailyHit::model()->find($criteria);

			$criteria = new CDbCriteria;
			$criteria->addCondition("campaign_id = '" . $value->campaign_id . "'");
			$criteria->addCondition("history_time = '" . strtotime(date("Y-m-d 00:00:00", $value->sync_time)) . "'");
			$history = CampaignBookingHistory::model()->find($criteria);	
			
			if($history === null)		
				$history = new CampaignBookingHistory();
			
			$history->campaign_id = $value->campaign_id;
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
				$history->run_click = $run->daily_hit_click;
				$history->run_imp = $run->daily_hit_pv;
				$history->run_budget = ceil($run->daily_hit_budget);
			}

			if(!$history->save()){
				// print_r($history); exit;
			}
		}

	}

}
