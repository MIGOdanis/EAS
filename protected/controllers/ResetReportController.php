<?php

class ResetReportController extends Controller
{
	public function actionClearReportByDay($day)
	{
		set_time_limit(0);
		$day = strtotime($day);
		$criteria=new CDbCriteria;
		$criteria->addCondition("settled_time = '" . $day . "'");	
		$criteria->addCondition("report_type = 1");		
		BuyReportDailyPc::model()->deleteAll($criteria);
	}

	public function actionCopyReportByDay($from, $to)
	{
		set_time_limit(0);
		$from = strtotime($from);
		$criteria=new CDbCriteria;
		$criteria->addCondition("settled_time = '" . $from . "'");		
		$criteria->addCondition("report_type = 1");	
		$fromReport = BuyReportDailyPc::model()->findAll($criteria);
		// print_r($criteria); exit;
		// print_r($fromReport); exit;
		foreach ($fromReport as $value) {
			$toReport = new BuyReportDailyPc();
			$toReport->tos_id = $value->id;
			$toReport->report_type = 1;
			$toReport->settled_time = strtotime($to);
			$toReport->campaign_id = $value->campaign_id;
			$toReport->ad_space_id = $value->ad_space_id;
			$toReport->strategy_id = $value->strategy_id;
			$toReport->creative_id = $value->creative_id;
			$toReport->media_category_id = $value->media_category_id;
			$toReport->screen_pos = $value->screen_pos;
			$toReport->adformat = $value->adformat;
			$toReport->width_height = $value->width_height;
			$toReport->pv = $value->pv;
			$toReport->impression = $value->impression;
			$toReport->impression_ten_sec = $value->impression_ten_sec;
			$toReport->click = $value->click;
			$toReport->media_cost = $value->media_cost;
			$toReport->media_tax_cost = $value->media_tax_cost;
			$toReport->media_ops_cost = $value->media_ops_cost;
			$toReport->income = $value->income;
			$toReport->income_ten_sec = $value->income_ten_sec;
			$toReport->agency_income = $value->agency_income;
			$toReport->sync_time = time();		
			if(!$toReport->save()){
				$totclick += $value->click;
				print_r($toReport->getErrors());
			}
		}

		print_r($totclick);
	}	

	public function actionReSetImpBySite()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		ini_set('display_errors', 1);

		$day = strtotime("2016-01-17 00:00:00");
		$criteria=new CDbCriteria;
		$criteria->addCondition("settled_time = '" .  strtotime("2016-01-15 00:00:00") . "' OR settled_time = '" .  strtotime("2016-01-16 00:00:00") . "'  OR settled_time = '" .  strtotime("2016-01-17 00:00:00") . "' ");
		$criteria->addCondition("report_type = 1");
		$criteria->addCondition("ad_space_id IN ('100208680','100408035')");
		print_r($criteria); exit;
		$site = BuyReportDailyPc::model()->findAll($criteria);
		
		foreach ($site as $value) {
			$criteria=new CDbCriteria;
			$criteria->addCondition("id = '" . $value->id . "'");				
			$newData = BuyReportDailyPc::model()->find($criteria);
			$newData->media_cost = ($newData->impression / 1000) * 550000;
			$newData->save();
			$mc = $newData->media_cost + $mc;
			// print_r($newData->media_cost . "<br>");
			// exit;
		}

	}

	public function actionReSetImpByDay()
	{
		set_time_limit(0);
		ini_set("memory_limit","4000M");
		ini_set('display_errors', 1);

		$day = strtotime("2016-01-17 00:00:00");
		$criteria=new CDbCriteria;

		$criteria->addCondition("settled_time = '" . $day . "'");
		$criteria->addCondition("report_type = 1");
		
		$fromReport = BuyReportDailyPc::model()->findAll($criteria);
		// print_r($fromReport); exit;

		foreach ($fromReport as $value) {
			$precent = 1.8;
			$criteria=new CDbCriteria;
			$criteria->addCondition("id = '" . $value->id . "'");				
			$newData = BuyReportDailyPc::model()->find($criteria);
			$cpm = array('100392398' => '30','100388277' => '3','100351579' => '1.25','100390258' => '1.5','100390266' => '1.5','100346794' => '2','100346802' => '2','100250117' => '10','100228829' => '2');
			if($newData != null){
				$newData->impression = $newData->impression * $precent;
				if(isset($cpm[$value->ad_space_id])){
					$newData->media_cost = ($newData->impression / 1000) * $cpm[$value->ad_space_id];
				}				
				$newData->save();
			}
		}
	}

	public function actionReSetImpBy0116()
	{	
		set_time_limit(0);
		ini_set("memory_limit","4000M");
		$day = strtotime("2016-01-16 00:00:00");
		$criteria=new CDbCriteria;
		$criteria->addCondition("settled_time = '" . $day . "'");	
		$criteria->addCondition("report_type = 1");		
		$fromReport = BuyReportDailyPc::model()->findAll($criteria);
		foreach ($fromReport as $value) {
			$precent = 1.75;
			$criteria=new CDbCriteria;
			$criteria->addCondition("id = '" . $value->id . "'");				
			$newData = BuyReportDailyPc::model()->find($criteria);
			$precentCust = array('100455','10012721','10012736','10013050','10013055','10010955','10010965','10012745','10012803','10013097','10013107','100232953','10013137','10013152','10013162','10013172','100231311','100231319','100264681','100464','10013323','10013328','10013333','100940','100945','100954','100963','100968','100977','100982','10013878','10013883','10013888','1001131','10013893','10013898','10013903','10013908','10013913','10013918','100200089');
			if(in_array($value->ad_space_id, $precentCust)){
				$precent = (rand(130,175) / 100);
			}
			if($newData != null){
				$newData->click = $newData->click * $precent;
				$newData->impression = $newData->impression * $precent;
				$newData->media_cost = $newData->media_cost * $precent;
				$newData->media_tax_cost = $newData->media_tax_cost * $precent;
				$newData->media_ops_cost = $newData->media_ops_cost * $precent;
				$newData->income = $newData->income * $precent;
				$newData->income_ten_sec = $newData->income_ten_sec * $precent;
				$newData->agency_income = $newData->agency_income * $precent;
				$newData->save();
			}
		}
	}	

	public function actionPrecent()
	{
		echo (rand(175,220) / 100);
	}

	public function actionCountByDay($day)
	{
		$day = strtotime($day);
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.income) / 100000 as income,
			sum(t.agency_income) / 100000 as agency_income,
			t.settled_time
		';
		$criteria->addCondition("settled_time = '" . $day . "'");
		$criteria->addCondition("report_type = 2");		
		$noPayCriteria = new CDbCriteria;
		$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
		$noPayCampaignId = array();
		foreach ($noPayCampaign as $value) {
			$noPayCampaignId[] = $value->tos_id;
		}
		$criteria->addNotInCondition("campaign_id",$noPayCampaignId);		
		$mf = BuyReportDailyPc::model()->find($criteria);
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.income) / 100000 as income,
			sum(t.agency_income) / 100000 as agency_income,
			t.settled_time
		';
		$criteria->addCondition("settled_time = '" . $day . "'");		
		$criteria->addCondition("report_type = 1");	
		$noPayCriteria = new CDbCriteria;
		$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
		$noPayCampaignId = array();
		foreach ($noPayCampaign as $value) {
			$noPayCampaignId[] = $value->tos_id;
		}
		$criteria->addNotInCondition("campaign_id",$noPayCampaignId);			
		$cf = BuyReportDailyPc::model()->find($criteria);

		echo 
		"Cf"
		."<br>曝光".$cf->impression
		."<br>點擊".$cf->click
		."<br>媒體成本".$cf->media_cost
		."<br>廣告主花費".$cf->income
		."<br>廣告主花費MU前".$cf->agency_income;

		echo 
		"<br>Mf"
		."<br>曝光".$mf->impression
		."<br>點擊".$mf->click
		."<br>媒體成本".$mf->media_cost
		."<br>廣告主花費".$mf->income
		."<br>廣告主花費MU前".$mf->agency_income;

	}		

}