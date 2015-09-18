<?php

class SiteController extends Controller
{
	public function actionError()
	{
		$this->layout = "column_list";
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionTodayHourly()
	{
		$criteria = new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.campaign_id as campaign_id
		';		
		$criteria->group = "t.campaign_id";		
		$criteria->addCondition("t.settled_time >= '" . date("Y-m-d 00:00:00") . "'");	
		$pc = TosTreporBuyDrDisplayHourlyPcReport::model()->findAll($criteria);
		$mob = TosTreporBuyDrDisplayHourlyMobReport::model()->findAll($criteria);

		$campaign = array();
		foreach ($pc as $value) {
			$campaign[$value->campaign_id] = array(
				"income" => $value->income,
				"click" => $value->click,
				"impression" => $value->impression,
			);
		}
		foreach ($mob as $value) {
			$campaign[$value->campaign_id] = array(
				"income" => $campaign[$value->campaign_id]['income'] + $value->income,
				"click" => $campaign[$value->campaign_id]['click'] + $value->click,
				"impression" => $campaign[$value->campaign_id]['impression'] + $value->impression,
			);
		}		

		foreach ($campaign as $key => $value) {
			echo "訂單" . $key . "花費 : " .  round($value['income']) . "點擊 : " .  $value['click'] . "曝光 : " .  $value['impression']  . "<br>";
		}	
	}

	public function actionRealTime($id)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("t.date = '" . date("Y-m-d 00:00:00") ."'");		
		$criteria->addCondition("t.campaign_id = '" . $id ."'");				
		$model = TosCoreCampaignDailyHit::model()->find($criteria);
		if($model === null){
			echo "今日沒有資料<br>";
			echo date("Y-m-d H:i:s");
		}else{
			echo "訂單編號 : " . $model->campaign_id;
			echo "<br>最後執行 : " . $model->last_changed;
			echo "<br>本日花費 : " . $model->daily_hit_budget / 100;
			echo "<br>本日曝光 : " . $model->daily_hit_pv;
			echo "<br>本日點擊 : " . $model->daily_hit_click;
			echo "<br>資料時間 : " . date("Y-m-d H:i:s");
		}
	}

	public function actionRealTimeStrategy($id)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("t.date = '" . date("Y-m-d 00:00:00") ."'");		
		$criteria->addCondition("t.strategy_id = '" . $id ."'");				
		$model = TosCoreStrategyDailyHit::model()->find($criteria);
		if($model === null){
			echo "今日沒有資料<br>";
			echo date("Y-m-d H:i:s");
		}else{
			echo "策略編號 : " . $model->strategy_id;
			echo "<br>最後執行 : " . $model->last_changed;
			echo "<br>本日花費 : " . $model->cost / 100;
			echo "<br>本日曝光 : " . $model->impression;
			echo "<br>本日點擊 : " . $model->click;			
			echo "<br>資料時間 : " . date("Y-m-d H:i:s");
		}
	}

	public function actionShowNoPay()
	{
		$noPayCriteria = new CDbCriteria;
		$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
		$noPayCampaignId = array();
		foreach ($noPayCampaign as $value) {
			$noPayCampaignId[] = $value->tos_id;
		}
		if($noPayCampaignId === null){
			echo "今日沒有資料<br>";
			echo date("Y-m-d H:i:s");
		}else{
			echo implode(",", $noPayCampaignId);
		}
	}


	public function actionFindNoPay()
	{
		$noPayCriteria = new CDbCriteria;
		$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
		$noPayCampaignId = array();
		foreach ($noPayCampaign as $value) {
			$noPayCampaignId[] = $value->tos_id;
		}
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as time
		';
		$criteria->addCondition("t.settled_time = '2015-08-12 00:00:00'");		
		$criteria->addInCondition("t.campaign_id", $noPayCampaignId);	
		$criteria->addCondition("t.ad_space_id = '10010725'");		
		$criteria->group = "t.campaign_id";
		$model = TosTreporBuyDrDisplayDailyPcReport::model()->findAll($criteria);		
		if($noPayCampaignId === null){
			echo "今日沒有資料<br>";
			echo date("Y-m-d H:i:s");
		}else{
			print_r($model);
		}
	}


	public function actionZoneHourly($id,$type)
	{
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as time
		';		
		$criteria->addCondition("t.settled_time >= '" . date("Y-m-d 00:00:00") ."'");		
		$criteria->addCondition("t.ad_space_id = '" . $id ."'");			
		$criteria->group = "t.ad_space_id";
		if($type == "1"){
			$model = TosTreporBuyDrDisplayHourlyPcReport::model()->find($criteria);
		}else{
			$model = TosTreporBuyDrDisplayHourlyMobReport::model()->find($criteria);
		}	
		
		if($model === null){
			echo "今日沒有資料<br>";
			echo date("Y-m-d H:i:s");
		}else{
			echo "版位編號 : " . $id;
			echo "<br>本日獲利 : $" . $model->media_cost;
			echo "<br>本日曝光 : " . $model->impression;
			echo "<br>本日點擊 : " . $model->click;
			echo "<br>資料時間 : " . date("Y-m-d H:i:s");
		}
	}		
}