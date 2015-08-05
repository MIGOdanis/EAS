<?php

class CronCheckHourlyController extends Controller
{

	public function actionCronHourlyPc()
	{
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$m = date("i");
		$now = time();

		$m = 15;
		if($m >= 45){
			$time = date("Y-m-d H:30:00", $now - 3600);
		}else{
			$time = date("Y-m-d H:00:00", $now - 3600);
		}

		echo $time;
		$this->writeLog("READY TO RUN REPORT @ " . $time,"CronHourlyPc/run",date("Ymd") . ".log");		

		$criteria = new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as time,
			t.campaign_id as campaign_id
		';
		$criteria->addCondition("t.settled_time = '" . $time . "'");
		$criteria->addCondition("t.campaign_id !=  0");
		$criteria->group = "settled_time, campaign_id";
		$criteria->order = "impression desc, click desc";

		$model = $this->tryGetHourlyPc(1,$criteria);

		if($model == false){
			echo "DB ERROR!!";
			exit;
		}

		if($model !== null){
			$html = "<p>報表時間 : " . $time. " + 30min</p>";
			$html .= '
			<table border="1" style="width:100%">
			<tr>
			<td>訂單編號</td>
			<td>訂單名稱</td> 
			<td>曝光</td>
			<td>點擊</td>
			<td>訂單花費</td>
			<td>媒體成本</td>
			</tr>
			';
		foreach ($model as $value) {

			$html .= '
			<tr>
			<td>' . $value->campaign_id . '</td>
			<td>' . $value->campaign->campaign_name . '</td> 
			<td>' . $value->impression . '</td>
			<td>' . $value->click . '</td>
			<td>' . number_format($value->income,2,".",",") . '</td>
			<td>' . number_format($value->media_cost,2,".",",") . '</td>
			</tr>
			';
		}
  		$html .= '</table>';
		}else{
			$html = "<p>報表時間 : " . $time. " + 30min</p>";
			$html .= '沒有資料';
		}
		foreach (Yii::app()->params['alertMailGroup'] as $mail) {
			$this->email($mail, "CLICKFORCE EAS AUTO 30MIN REPORT: PC" . $time, $html);
		}
	}

	function tryGetHourlyPc($try=0,$criteria){
		try {   
			$model = TosTreporBuyDrDisplayHourlyPcReport::model()->findAll($criteria);  
			return $model;
		} catch (Exception $e) { 
			$this->writeLog("ERR : (TRY : " . $try ." ) ". $e->getMessage(),"CronHourlyPc/error",date("Ymd") . "errorLog.log");		
			$try++;

			if($try > 3){
				foreach (Yii::app()->params['alertMailGroup'] as $mail) {
					$this->email($mail, "CLICKFORCE EAS ALERT : 時報檢查失敗 => DB Error After Retry! (Pc)", $e->getMessage());
				}
				return false;
			}else{
				sleep(5);
				$model = $this->tryGetHourlyPc($try,$criteria);
			}
				 
		}	
	}

	public function actionCronHourlyMob()
	{
		defined('YII_DEBUG') or define('YII_DEBUG',true);
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$m = date("i");
		$now = time();

		$m = 15;
		if($m >= 45){
			$time = date("Y-m-d H:30:00", $now - 3600);
		}else{
			$time = date("Y-m-d H:00:00", $now - 3600);
		}

		echo $time;
		$this->writeLog("READY TO RUN REPORT @ " . $time,"CronHourlyMob/run",date("Ymd") . ".log");		

		$criteria = new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as time,
			t.campaign_id as campaign_id
		';
		$criteria->addCondition("t.settled_time = '" . $time . "'");
		$criteria->addCondition("t.campaign_id !=  0");
		$criteria->group = "settled_time, campaign_id";
		$criteria->order = "impression desc, click desc";

		$model = $this->tryGetHourlyMob(1,$criteria);

		if($model == false){
			echo "DB ERROR!!";
			exit;
		}

		if($model !== null){
			$html = "<p>報表時間 : " . $time. " + 30min</p>";
			$html .= '
			<table border="1" style="width:100%">
			<tr>
			<td>訂單編號</td>
			<td>訂單名稱</td> 
			<td>曝光</td>
			<td>點擊</td>
			<td>訂單花費</td>
			<td>媒體成本</td>
			</tr>
			';
		foreach ($model as $value) {

			$html .= '
			<tr>
			<td>' . $value->campaign_id . '</td>
			<td>' . $value->campaign->campaign_name . '</td> 
			<td>' . $value->impression . '</td>
			<td>' . $value->click . '</td>
			<td>' . number_format($value->income,2,".",",") . '</td>
			<td>' . number_format($value->media_cost,2,".",",") . '</td>
			</tr>
			';
		}
  		$html .= '</table>';
		}else{
			$html = "<p>報表時間 : " . $time. " + 30min</p>";
			$html .= '沒有資料';
		}
		foreach (Yii::app()->params['alertMailGroup'] as $mail) {
			$this->email($mail, "CLICKFORCE EAS AUTO 30MIN REPORT: PC" . $time, $html);
		}
	}

	function tryGetHourlyMob($try=0,$criteria){
		try {   
			$model = TosTreporBuyDrDisplayHourlyMobReport::model()->findAll($criteria);  
			return $model;
		} catch (Exception $e) { 
			$this->writeLog("ERR : (TRY : " . $try ." ) ". $e->getMessage(),"CronHourlyMob/error",date("Ymd") . "errorLog.log");		
			$try++;

			if($try > 3){
				foreach (Yii::app()->params['alertMailGroup'] as $mail) {
					$this->email($mail, "CLICKFORCE EAS ALERT : 時報檢查失敗 => DB Error After Retry ! (Mob)", $e->getMessage());
				}
				return false;
			}else{
				sleep(5);
				$model = $this->tryGetHourlyPc($try,$criteria);
			}
				 
		}	
	}



}