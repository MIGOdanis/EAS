<?php

class BookingReportController extends Controller
{

	public function actionCampaign($id)
	{
		$this->layout = "column1";
		$model = new Booking('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Booking']))
			$model->attributes=$_GET['Booking'];

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.tos_id = '" . $id . "'");
		$campaign = Campaign::model()->find($criteria);


		$this->render('campaign',array(
			'model'=>$model,
			'campaign'=>$campaign
		));
	}

	public function actionCampaignListHistory()
	{
		$this->layout = "column1";
		$model = new Booking('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Booking']))
			$model->attributes=$_GET['Booking'];

		$this->render('campaignListHistory',array(
			'model'=>$model
		));
	}

	public function actionFilterCampaign()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.booking_time >= '" . strtotime(date("Y-m-d") . ' -5 day') . "'");
		$criteria->addCondition("t.booking_time <= '" . strtotime(date("Y-m-d") . ' +15 day') . "'");
		$criteria->group = "t.campaign_id";	
		$model = Booking::model()->with("campaign")->findAll($criteria);
		$this->renderPartial('filterCampaign',array(
			"model" => $model
		));
	}

	public function actionWeekBooking()
	{
		$lastBooking = Log::model()->getValByName("lastCronBooking");

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


		$futureArray = array();
		for ($i=0; $i <= 15; $i++) {
			$criteria = new CDbCriteria;
			$criteria->select = '
				sum(t.booking_click) as booking_click,
				sum(t.day_click) as day_click,
				sum(t.booking_imp) as booking_imp,
				sum(t.day_imp) as day_imp,
				sum(t.booking_budget) as booking_budget,
				sum(t.day_budget) as day_budget,
				booking_time as booking_time,
				update_time as update_time
			';				
			$criteria->addCondition("t.status = '1'");
			$criteria->addCondition("t.booking_time = '" . strtotime(date("Y-m-d 00:00:00") . "+" . $i . "day") . "'");
			if(isset($_GET['type']) && $_GET['type'] > 0)
				$criteria->addCondition("t.type = " . (int)$_GET['type']);

			$criteria->addNotInCondition("campaign_id", $noPayCampaignId);
			$booking = Booking::model()->find($criteria);
			$futureArray[] = $booking;
		}

		$pastArray = array();
		for ($i=1; $i <= 5; $i++) {
			$pastArray[$i] = $this->countByPastDay($i,$noPayCampaignId);
		}
		
		krsort($pastArray);

		$this->render('weekBooking',array(
			'future'=>$futureArray,
			'past' => $pastArray,
			'lastBooking' => $lastBooking
		));
	}	


	public function countByPastDay($day,$noPayCampaignId)
	{
		$day = strtotime(date("Y-m-d 00:00:00") . ' -' . $day. ' day');


		$criteria = new CDbCriteria;
		$criteria->select = '
			sum(t.booking_click) as booking_click,
			sum(t.day_click) as day_click,
			sum(t.run_click) as run_click,
			sum(t.booking_imp) as booking_imp,
			sum(t.day_imp) as day_imp,
			sum(t.run_imp) as run_imp,
			sum(t.booking_budget) as booking_budget,
			sum(t.day_budget) as day_budget,
			(sum(t.run_budget) ) as run_budget,
			booking_time as booking_time,
			campaign_id as campaign_id

		';	
		$criteria->addCondition("t.booking_time = '" . $day . "'");

		if(isset($_GET['type']) && $_GET['type'] > 0)
			$criteria->addCondition("t.type = " . (int)$_GET['type']);

		$criteria->addNotInCondition("campaign_id", $noPayCampaignId);

		$model = Booking::model()->find($criteria);
		return $model;

	}
}