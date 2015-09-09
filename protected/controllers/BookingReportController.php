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

	public function actionCampaignListHistory()
	{

		$model = new CampaignBookingHistory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CampaignBookingHistory']))
			$model->attributes=$_GET['CampaignBookingHistory'];

		$this->render('campaignListHistory',array(
			'model'=>$model
		));
	}

	public function actionFilterCampaign()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("remaining_day > 0");	
		$criteria->group = "t.campaign_id";
		$future = CampaignBooking::model()->with("campaign")->findAll($criteria);	
		$campaignId = array();
		foreach ($future as $value) {
			$campaignId[] = $value->campaign->tos_id;
		}	
		$criteria = new CDbCriteria;
		$criteria->group = "t.campaign_id";
		$criteria->addNotInCondition("t.campaign_id", $campaignId);
		$criteria->addCondition("t.history_time >= '" . strtotime(date("Y-m-d") . ' -5 day') . "'");
		$past = CampaignBookingHistory::model()->with("campaign")->findAll($criteria);

		$this->renderPartial('filterCampaign',array(
			'future'=>$future,
			'past'=>$past,
		));
	}

	public function actionWeekBooking()
	{
		if(isset($_GET['resetFilter'])){
			unset($_COOKIE['noPayCampaignId']);
		}

		$noPayCampaignId = array();

		if(isset($_POST['noPayCampaignId']) && !empty($_POST['noPayCampaignId'])){
			$noPayCampaignId = $_POST['noPayCampaignId'];
		}else if(isset($_COOKIE['noPayCampaignId']) && !empty($_COOKIE['noPayCampaignId'])){
			$noPayCampaignId = explode(":", $_COOKIE['noPayCampaignId']);
		}

		if(empty($noPayCampaignId)){
			$noPayCriteria = new CDbCriteria;
			$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noBookingAdvertiser']);		
			$noPayCampaign = TosCoreCampaign::model()->findAll($noPayCriteria);
			foreach ($noPayCampaign as $value) {
				$noPayCampaignId[] = $value->id;
			}		
		}

		setcookie("noPayCampaignId", implode(":", $noPayCampaignId), time() + 3600);

		$criteria = new CDbCriteria;
		$criteria->addCondition("remaining_day > 0");
		$criteria->addNotInCondition("campaign_id", $noPayCampaignId);
		

		if(isset($_GET['type']) && $_GET['type'] > 0)
			$criteria->addCondition("t.type = " . (int)$_GET['type']);

		$future = CampaignBooking::model()->findAll($criteria);
		// print_r($future); exit;
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
			(sum(t.run_budget) ) as run_budget
		';	
		$criteria->addCondition("t.history_time = '" . $day . "'");

		if(isset($_GET['type']) && $_GET['type'] > 0)
			$criteria->addCondition("t.type = " . (int)$_GET['type']);

		$criteria->addNotInCondition("campaign_id", $noPayCampaignId);
		$model = CampaignBookingHistory::model()->find($criteria);
		return $model;

	}
}