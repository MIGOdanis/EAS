<?php

class CronBookingController extends Controller
{
	public function actionCronCountBooking()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		//取出走期內的所有訂單
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.start_time <= '" . date("Y-m-d H:i:s", strtotime(date("Y-m-d") . ' +16 day')) . "'");
		$criteria->addCondition("t.end_time >= '" . date("Y-m-d H:i:s") . "'");
		$criteria->addCondition("t.status = 1");

		if(isset($_GET['test']) && isset($_GET['id']))
			$criteria->addCondition("t.id = '" . $_GET['id'] . "'");
		
		$campaign = TosCoreCampaign::model()->with("budget","totalHit","strategy","strategy.strategyBudget","strategy.strategyTotalHit")->findAll($criteria);
		foreach ($campaign as $value) {
			$campaignBookingDay = strtotime($value->end_time) -  strtotime($value->start_time);
			$campaignBookingDay = ceil($campaignBookingDay / 86400);
			if($campaignBookingDay == 0)
				$campaignBookingDay = 1;

			if($value->totalHit->total_hit_pv > 0){
				$campaignCTR = (($value->totalHit->total_hit_click / $value->totalHit->total_hit_pv) * 100);
				if($campaignCTR < 0.07)
					$campaignCTR = 0.07;
			}else{
				$campaignCTR = 0.07;
			}

			$campaignBudget = $this->transCampaignBudget($value->budget,$value->totalHit,$campaignBookingDay,$campaignCTR);

			$maxDay = $campaignBookingDay;
			if($maxDay > 30)
				$maxDay = 30;

			for ($i=0; $i < $maxDay; $i++) { 
				$day = strtotime(date("Y-m-d 00:00:00") . "+" . $i . "day");
				$strategy = $this->transStrategyBudget($value,$campaignBudget,$day);

				if(isset($_GET['test'])){
					print_r($strategy);
					exit;
				}

				if(!empty($strategy)){
					foreach ($strategy as $strategyData) {

						$criteria = new CDbCriteria;
						$criteria->addCondition("t.strategy_id = '" . $strategyData['id'] . "'");
						$criteria->addCondition("t.booking_time = '" . $strategyData['day'] . "'");
						$model = Booking::model()->find($criteria);

						if($model === null)
							$model = new Booking();
						
						$model->campaign_id = $value->id;
						$model->strategy_id = $strategyData['id'];
						$model->type = $strategyData['type'];
						$model->booking_click = $strategyData['totalClick'];
						$model->day_click = $strategyData['dayClick'];
						$model->run_click = 0;
						$model->click_status = $strategyData['budgetStatus'];  
						$model->booking_imp = $strategyData['totalPv'];
						$model->day_imp = $strategyData['dayPv'];
						$model->run_imp = 0;
						$model->imp_status = $strategyData['pvStatus'];
						$model->booking_budget = $strategyData['totalBudget'];
						$model->day_budget = $strategyData['dayBudget'];
						$model->run_budget = 0;
						$model->budget_status = $strategyData['budgetStatus'];
						$model->booking_time = $strategyData['day'];
						$model->update_time = time();
						$model->status = 1;
						if(!$model->save()){
							print_r($model->getErrors());
							print_r($model);
							// exit;
						}

					}
				}				
			}

			$this->updateStrategyStatus($value->id);

		}
	}


	public function updateStrategyStatus($campaignId){
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$model = TosCoreStrategy::model()->findAll($criteria);
		foreach ($model as $value) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("strategy_id = '" . $value->id . "'");
			$criteria->addCondition("booking_time >= '" . strtotime(date("Y-m-d 00:00:00")) . "'");

			$status = 1;
			if($value->status != 1){
				$status = 0;
			}
			Booking::model()->updateAll(array(
				'status' => $status
			),$criteria);
		}
	}

	public function getStrategyPrice($strategy){
		$price = 5;
		if($strategy->kpi_value > 0){
			$price = ($strategy->kpi_value / 100);
		}elseif($strategy->bidding_price > 0){
			$price = ($strategy->bidding_price / 100);
		}

		return $price;
	}


	public function transStrategyBudget($data,$campaignBudget,$day){

		$strategy = array();
		$countTotalBudget = 0;
		$countDayBudget = 0;
		$countTotalClick = 0;
		$countDayClick = 0;
		$countTotalPv = 0;
		$countDayPv = 0;

		$countStrategy = count($data->strategy);
		if($countStrategy < 1){
			//沒有策略直接結束
			return $strategy;
		}

		foreach ($data->strategy as $value) {
			$startTime = strtotime($value->start_time);
			$endTime = strtotime($value->end_time);

			if($startTime <= $day && $endTime >= $day){

				$budget = $value->strategyBudget;

				$budgetStatus = 1;
				$totalBudget = ceil($budget->total_budget / 100);
				if($totalBudget == 0){
					$totalBudget = $campaignBudget['totalBudget'];
					$budgetStatus = 0;
				}


				$dayBudget = ceil($budget->max_daily_budget / 100);
				if($dayBudget == 0){
					$hitCost = ceil($value->strategyTotalHit->cost / 100);
					if(isset($_GET['update']))
						$hitCost = 0;					
					$dayBudget = round( ($totalBudget - $hitCost) / $campaignBudget['bookingDay']);
					$budgetStatus = 0;
				}				


				if($value->kpi_type == 2){
					//CPC計價方式
					//點擊
					$cpc = $this->getStrategyPrice($value);

					if(isset($_GET['test']))
						echo $value->id . "採CPM" . $cpc . "計價<br>";

					$clickStatus = 1;
					$totalClick = $budget->total_click;
					if($totalClick == 0){
						$totalClick = round($totalBudget / $cpc);
						$clickStatus = 0;
					}	

					$dayClick = $budget->max_daily_click;
					if($dayClick == 0){
						$dayClick = round($dayBudget / $cpc);
						$clickStatus = 0;
					}

					
					if($data->strategy->strategyTotalHit->impression > 0){
						$CTR = (($value->strategyTotalHit->click / $value->strategyTotalHit->impression) * 100);
						if($CTR < 0.07)
							$CTR = 0.07;
					}else{
						$CTR = 0.07;
					}

					//曝光
					$pvStatus = 1;
					$totalPv = $budget->total_imp;
					if($totalPv == 0){
						$totalPv = round(($totalClick / $CTR)  * 100);
						$pvStatus = 0;
					}	

					$dayPv = $budget->max_daily_imp;
					if($dayPv == 0){
						$dayPv = round(($dayClick / $CTR)  * 100);
						$pvStatus = 0;
					}						
				}else{
					//曝光計價先算曝光
					//曝光
					//1個IMP價格
					$cpm = $this->getStrategyPrice($value);

					if(isset($_GET['test']))
						echo $value->id . "採CPM" . $cpm . "計價<br>";

					$pvStatus = 1;
					$totalPv = $budget->total_imp;
					if($totalPv == 0){
						$totalPv = round($totalBudget / $cpm) * 1000;
						$pvStatus = 0;
					}	

					$dayPv = $budget->max_daily_imp;
					if($dayPv == 0){
						$dayPv = round($dayBudget / $cpm) * 1000;
						$pvStatus = 0;
					}	

					if($value->medium == 1){
						$ctr = 0.001;
					}else{
						$ctr = 0.0025;
					}	

					$clickStatus = 1;
					$totalClick = $budget->total_click;
					if($totalClick == 0){
						$totalClick = round($totalPv * $ctr);
						$clickStatus = 0;
					}

					$dayClick = $budget->max_daily_click;
					if($dayClick == 0){
						$dayClick = round($dayPv * $ctr);
						$clickStatus = 0;
					}

				}

				$strategy[] = array(
					"id" => $value->id,
					"totalBudget" => $totalBudget,
					"dayBudget" => $dayBudget,
					"budgetStatus" => $budgetStatus,
					"totalClick" => $totalClick,
					"dayClick" => $dayClick,
					"clickStatus" => $clickStatus,
					"totalPv" => $totalPv,
					"dayPv" => $dayPv,
					"pvStatus" => $pvStatus,
					"type" => $value->medium,
					"day" => $day	
				);

				$countTotalBudget = $countTotalBudget + $totalBudget;
				$countDayBudget = $countDayBudget + $dayBudget;
				$countTotalClick = $countTotalClick + $totalClick;
				$countDayClick = $countDayClick + $dayClick;
				$countTotalPv = $countTotalPv + $totalPv;
				$countDayPv = $countDayPv + $dayPv;

			}
			 
		}

		//如果數值超過訂單值則平攤
		if($countTotalBudget > $campaignBudget['totalBudget']){
			$value =  round($campaignBudget['totalBudget'] / $countStrategy);
			$strategy = $this->resetValue($value,'totalBudget',$strategy);
			$strategy = $this->resetValue(2,'budgetStatus',$strategy);
			if(isset($_GET['test']))
				echo "策略總花費" . $countTotalBudget . "超過訂單可用值" . $campaignBudget['totalBudget'] . " => 重設為" . $value . "<br>";
		}		


		if($countDayBudget > $campaignBudget['dayBudget']){
			$value =  round($campaignBudget['dayBudget'] / $countStrategy);
			$strategy = $this->resetValue($value,'dayBudget',$strategy);
			$strategy = $this->resetValue(2,'budgetStatus',$strategy);
			if(isset($_GET['test']))
				echo "策略日花費" . $countDayBudget . "超過訂單可用值" . $campaignBudget['dayBudget'] . " => 重設為" . $value . "<br>";			
		}	

		if($countTotalClick > $campaignBudget['totalClick']){
			$value =  round($campaignBudget['totalClick'] / $countStrategy);
			$strategy = $this->resetValue($value,'totalClick',$strategy);
			$strategy = $this->resetValue(2,'clickStatus',$strategy);
			if(isset($_GET['test']))
				echo "策略總點擊" . $countTotalClick . "超過訂單可用值" . $campaignBudget['totalClick'] . " => 重設為" . $value . "<br>";			
		}	

		if($countDayClick > $campaignBudget['dayClick']){
			$value =  round($campaignBudget['dayClick'] / $countStrategy);
			$strategy = $this->resetValue($value,'dayClick',$strategy);
			$strategy = $this->resetValue(2,'clickStatus',$strategy);
			if(isset($_GET['test']))
				echo "策略日點擊" . $countDayClick . "超過訂單可用值" . $campaignBudget['dayClick'] . " => 重設為" . $value . "<br>";			
		}	

		if($countTotalPv > $campaignBudget['totalPv']){
			$value =  round($campaignBudget['totalPv'] / $countStrategy);
			$strategy = $this->resetValue($value,'totalPv',$strategy);
			$strategy = $this->resetValue(2,'pvStatus',$strategy);
			if(isset($_GET['test']))
				echo "策略總曝光" . $countTotalPv . "超過訂單可用值" . $campaignBudget['totalPv'] . " => 重設為" . $value . "<br>";			

		}	

		if($countDayPv > $campaignBudget['dayPv']){
			$value =  round($campaignBudget['dayPv'] / $countStrategy);
			$strategy = $this->resetValue($value,'dayPv',$strategy);
			$strategy = $this->resetValue(2,'pvStatus',$strategy);
			if(isset($_GET['test']))
				echo "策略日曝光" . $countDayPv . "超過訂單可用值" . $campaignBudget['dayPv'] . " => 重設為" . $value . "<br>";			
		}	
		
		return $strategy;

	}

	public function resetValue($value,$key,$strategy){
		for ($i=0; $i < count($strategy); $i++) { 
			$strategy[$i][$key] = $value;
		}		

		return $strategy;
	}


	public function transCampaignBudget($budget,$totalHit,$campaignBookingDay,$campaignCTR){
		//預算
		$totalBudget = ceil($budget->total_budget / 100);

		$dayBudget = ceil($budget->max_daily_budget / 100);
		if($dayBudget == 0){
			$totalHitBudget = ceil($totalHit->total_hit_budget / 100);
			if(isset($_GET['update']))
				$totalHitBudget = 0;
			$dayBudget = round( ($totalBudget - $totalHitBudget) / $campaignBookingDay);
		}

		//點擊
		$totalClick = $budget->total_click;
		if($totalClick == 0){
			$totalClick = round($totalBudget / 5);
		}	

		$dayClick = $budget->max_daily_click;
		if($dayClick == 0){
			$dayClick = round($dayBudget / 5);
		}

		//曝光
		$totalPv = $budget->total_pv;
		if($totalPv == 0){
			$totalPv = round(($totalClick / $campaignCTR)  * 100);
		}	

		$dayPv = $budget->max_daily_pv;
		if($dayPv == 0){
			$dayPv = round(($dayClick / $campaignCTR)  * 100);
		}	

		return array(
			"bookingDay" => $campaignBookingDay,
			"totalBudget" => $totalBudget,
			"dayBudget" => $dayBudget,
			"totalClick" => $totalClick,
			"dayClick" => $dayClick,
			"totalPv" => $totalPv,
			"dayPv" => $dayPv,
		);
	}

	public function actionCronCountBookingLog()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.status = 1");
		$criteria->addCondition("t.booking_time < " . strtotime(date("Y-m-d 00:00:00")));
		
		// $criteria->addCondition("t.campaign_id = 100278994");
		$model = Booking::model()->findAll($criteria);
		foreach ($model as $value) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("t.id = '" . $value->id . "'");
			$strategy = Booking::model()->find($criteria);
			if($strategy !== null){
				$criteria = new CDbCriteria;
				$criteria->addCondition("t.strategy_id = '" . $value->strategy_id . "'");				
				$criteria->addCondition("t.date = '" . date("Y-m-d 00:00:00", $value->booking_time) . "'");	
				$dailyHit = TosCoreStrategyDailyHit::model()->find($criteria);

				if($dailyHit === null){
					$strategy->run_click = 0;
					$strategy->run_imp = 0;
					$strategy->run_budget = 0;
				}else{
					$strategy->run_click = $dailyHit->click;
					$strategy->run_imp = $dailyHit->impression;
					$strategy->run_budget = ceil($dailyHit->cost / 100);
				}			
				$strategy->update_time = time();
				$strategy->status = 2;		
				$strategy->save();		
			}
		}
	}

}
