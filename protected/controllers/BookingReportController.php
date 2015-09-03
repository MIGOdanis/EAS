<?php

class BookingReportController extends Controller
{
	public function actionCampaignList()
	{


		$model = new CampaignBooking('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CampaignBooking']))
			$model->attributes=$_GET['CampaignBooking'];

		$this->render('campaignList',array(
			'model'=>$model
		));
	}

	public function actionWeekBooking()
	{
		$noPayCriteria = new CDbCriteria;
		$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noBookingAdvertiser']);		
		$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
		$noPayCampaignId = array();
		foreach ($noPayCampaign as $value) {
			$noPayCampaignId[] = $value->tos_id;
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("remaining_day > 0");
		$criteria->addNotInCondition("campaign_id", $noPayCampaignId);
		$future = CampaignBooking::model()->findAll($criteria);
		// print_r($future); exit;
		// $criteria = new CDbCriteria;
		// $criteria->addCondition("remaining_day > 0");
		// $criteria->addCondition("remaining_day < 5");
		// $criteria->addNotInCondition("campaign_id", $noPayCampaignId);
		// $past = CampaignBookingHistory::model()->findAll($criteria);

		$futureArray = array();
		for ($i=0; $i <= 15; $i++) {
			$count = $this->countByDay($i,$future); 
			$futureArray[] = array(
				"date" => date('Y-m-d', strtotime(date("Y-m-d") . ' +' . $i. ' day')),
				"day" => $i,
				"day_budget" => $count['budget'],
				"day_imp" => $count['imp'],
				"day_click" => $count['click'],
			);
		}

		// exit;
		$pastArray = array();
		for ($i=1; $i <= 5; $i++) {
			$count = $this->countByPastDay($i,$noPayCampaignId); 
			$pastArray[$i] = array(
				"date" => date('Y-m-d', strtotime(date("Y-m-d") . ' -' . $i. ' day')),
				"day" => $i,
				"day_budget" => (int)$count->day_budget,
				"day_imp" => (int)$count->day_imp,
				"day_click" => (int)$count->day_click,
				"run_budget" => (int)$count->run_budget,
				"run_imp" => (int)$count->run_imp,
				"run_click" => (int)$count->run_click,				
			);
		}
		
		krsort($pastArray);
		// print_r($pastArray); exit;


		$this->render('weekBooking',array(
			'future'=>$futureArray,
			'past' => $pastArray,
		));
	}	

	public function countByDay($day,$model)
	{
		$remaining_day = $day + 1;

		$click = 0;
		$imp = 0;
		$budget = 0;

		// echo $day . "-------------------------------<br>";
		foreach ($model as $value) {
			$campaignDay = $value->remaining_day - $day;
			if($value->remaining_day >= $remaining_day && $campaignDay <= $value->booking_day && $campaignDay > 0){
				// print_r($value->campaign_id."<br>");
				$click = $click + $value->day_click;
				$imp = $imp + $value->day_imp;
				$budget = $budget + $value->day_budget;				
			}
		}


		return array(
			"click" => $click,
			"imp" => $imp,
			"budget" => $budget,
		);
	}

	public function countByPastDay($day,$noPayCampaignId)
	{
		$day = strtotime(date("Y-m-d 00:00:00") . ' -' . $day. ' day');
		$criteria = new CDbCriteria;
		$criteria->select = '
			sum(t.booking_click) as booking_click,
			sum(t.remaining_click) as remaining_click,
			sum(t.day_click) as day_click,
			sum(t.run_click) as run_click,
			sum(t.booking_imp) as booking_imp,
			sum(t.remaining_imp) as remaining_imp,
			sum(t.day_imp) as day_imp,
			sum(t.run_imp) as run_imp,
			sum(t.booking_budget) as booking_budget,
			sum(t.remaining_budget) as remaining_budget,
			sum(t.day_budget) as day_budget,
			(sum(t.run_budget) / 100) as run_budget
		';	
		$criteria->addCondition("t.history_time = '" . $day . "'");
		$criteria->group = "t.history_time";
		$criteria->addNotInCondition("campaign_id", $noPayCampaignId);
		$model = CampaignBookingHistory::model()->find($criteria);
		return $model;

	}
}