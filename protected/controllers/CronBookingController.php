<?php

class CronBookingController extends Controller
{
	public function actionCronCountBooking()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.start_time <= '" . date("Y-m-d H:i:s") . "'");
		$criteria->addCondition("t.end_time >= '" . date("Y-m-d H:i:s") . "'");
		$criteria->addCondition("t.status = 1");
		$onTimeCampaign = TosCoreCampaign::model()->with("budget","totalHit")->findAll($criteria);
		foreach ($onTimeCampaign as $value) {
			
			$bookingDay = strtotime($value->end_time) -  strtotime($value->start_time);
			$bookingDay = round($bookingDay / 86400);

			$remainingDay = strtotime($value->end_time) - time();
			$remainingDay = round($remainingDay / 86400);
			if($remainingDay < 1)
				$remainingDay = 0;

			$criteria = new CDbCriteria;
			$criteria->addCondition("t.campaign_id = '" . $value->id . "'");
			$booking = CampaignBooking::model()->find($criteria);
			
			if($booking === null)
				$booking = new CampaignBooking();

			$booking->campaign_id = $value->id;

			
			$booking->booking_day = $bookingDay;
			$booking->remaining_day = $remainingDay;

			$bookingClick = $value->budget->total_click;
			if($bookingClick == 0)
				$bookingClick = $this->transClick($budget);

			$booking->booking_click = $bookingClick;
			$booking->remaining_click = $value->totalHit->total_hit_click;

			$booking->booking_imp = $value->budget->total_pv;
			$booking->remaining_imp = $value->totalHit->total_hit_pv;
			$booking->booking_budget = ($value->budget->total_budget / 100);
			$booking->remaining_budget = (int)$value->totalHit->total_hit_budget;
			$booking->start_time = strtotime($value->start_time);
			$booking->end_time = strtotime($value->end_time);
			$booking->sync_time = time();
			$booking->save();
		}

	}

	public function transClick($budget){
		return 0;
	}
}

			// echo $value->campaign_name . " : " . $day  . " : " . $nowDay   . " ( " . $value->start_time  . " : " . $value->end_time;
			// echo "<br>";
			// echo ($value->budget->total_budget / 100) . " : " . $value->budget->total_pv . " : " . $value->budget->total_click;
			// echo "<br>";