<?php

class CronCheckHourlyController extends Controller
{

	public function actionCronHourlyPc($type)
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$now = time();

		$stime = date("Y-m-d H:30:00", $now - 5600);
		$etime = date("Y-m-d H:00:00", $now - 3600);
		
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
		$criteria->addCondition("t.settled_time = '" . $stime . "' OR t.settled_time = '" . $etime . "'");
		$criteria->addCondition("t.campaign_id !=  0");
		$criteria->group = "campaign_id";
		$criteria->order = "impression desc, click desc";
		// print_r($criteria); exit;

		if($type == "PC"){
			$model = $this->tryGetHourlyPc(1,$criteria);
		}else if($type == "MOB"){
			$model = $this->tryGetHourlyMob(1,$criteria);
		}else{
			echo "TYPE ERROR!!";
			exit;			
		}
		

		if($model == "DB_ERROR"){
			echo "DB ERROR!!";
			exit;
		}


		$reportTime = "<p>報表時間 : " . $stime . " ~ " . date("Y-m-d H:30:00", $now - 3600) ."</p>";
		if($model !== null && !empty($model)){
			$html .= '
			<table border="1" style="width:100%">
				<tr>
				<td>訂單編號</td>
				<td>訂單名稱</td> 
				<td>小時曝光<br>本日曝光</td>
				<td>小時點擊<br>本日點擊</td>
				<td>小時CTR<br>本日CTR</td>
				<td>小時訂單花費<br>本日訂單花費</td>
				<td>當前eCPC</td>
				<td>當前eCPM</td>
				<td>小時媒體成本<br>本日媒體成本</td>
				</tr>
			';
			foreach ($model as $value) {
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
				$criteria->addCondition("t.settled_time >= '" . date("Y-m-d 00:00:00") . "'");
				$criteria->addCondition("t.campaign_id = '". $value->campaign_id . "'");
				$criteria->group = "campaign_id";

				if($type == "PC"){
					$daily = $this->tryGetDailyPc(1,$criteria);
				}else if($type == "MOB"){
					$daily = $this->tryGetDailyMob(1,$criteria);
				}

				$html .= '
				<tr>
				<td>' . $value->campaign_id . '</td>
				<td>' . $value->campaign->campaign_name . '</td> 
				<td>' . number_format($value->impression,0,".",",") . '<br>' . number_format($daily->impression,0,".",",") . '</td>
				<td>' . number_format($value->click,0,".",",") . '<br>' . number_format($daily->click,0,".",",") . '</td>
				<td>
				' . number_format((($value->impression == 0)? "0" : (($value->click / $value->impression) * 100)),2,".",",") . '%' . '<br>'
				  . number_format((($daily->impression == 0)? "0" : (($daily->click / $daily->impression) * 100)),2,".",",") . '%' .
				'</td>
				<td>$' . number_format($value->income,2,".",",") . '<br>$' . number_format($daily->income,2,".",",") . '</td>
				<td>$' . number_format((($daily->click == 0)? 0 : ($daily->income / $daily->click)),2,".",",") . '</td>
				<td>$' . number_format((($daily->impression == 0)? 0 : ($daily->income / $daily->impression) * 1000),2,".",",")  . '</td>
				<td>$' . number_format($value->media_cost,2,".",",") . '<br>$' . number_format($daily->media_cost,2,".",",") . '</td>
				</tr>
				';
				$impression = $impression+$value->impression;
				$click = $click+$value->click;
				$income = $income+$value->income;
				$media_cost = $media_cost+$value->media_cost;

				$dailyImpression = $dailyImpression + $daily->impression;
				$dailyClick = $dailyClick+$daily->click;
				$dailyIncome = $dailyIncome+$daily->income;
				$dailyMedia_cost = $dailyMedia_cost+$daily->media_cost;				
			}

			$tot .= '
			<p> 裝置 : ' . $type . '</p>
			<p> 總計</p>
			<table border="1" style="width:100%">
				<tr>
					<td>時間</td>
					<td>曝光</td>
					<td>點擊</td>
					<td>CTR</td>
					<td>訂單花費</td>
					<td>eCPC</td>
					<td>eCPM</td>
					<td>媒體成本</td>
					<td>分成百分比</td>
				</tr>	
				<tr>
					<td>這個小時 ('. $stime . " ~ " . date("Y-m-d H:30:00", $now - 3600) .')</td>
					<td>' . number_format($impression,0,".",",") . '</td>
					<td>' . number_format($click,0,".",",") . '</td>
					<td>' . number_format(($impression == 0)? "0" : (($click / $impression) * 100),2,".",",") . '%</td>
					<td>$' . number_format($income,2,".",",") . '</td>
					<td>$' . number_format((($click == 0)? 0 : ($income / $click)),2,".",",") . '</td>
					<td>$' . number_format((($impression == 0)? 0 : ($income / $impression) * 1000),2,".",",")  . '</td>
					<td>' . number_format($media_cost,2,".",",") . '</td>
					<td>' . number_format((($income == 0)? 0 : ($media_cost / $income)) * 100,2,".",",") . '%</td>
				</tr>	
				<tr>
					<td>本日當前 ('. date("Y-m-d"). ')</td>
					<td>' . number_format($dailyImpression,0,".",",") . '</td>
					<td>' . number_format($dailyClick,0,".",",") . '</td>
					<td>' . number_format(($dailyImpression == 0)? "0" : (($dailyClick / $dailyImpression) * 100),2,".",",") . '%</td>
					<td>$' . number_format($dailyIncome,2,".",",") . '</td>
					<td>$' . number_format((($dailyClick == 0)? 0 : ($dailyIncome / $dailyClick)),2,".",",") . '</td>
					<td>$' . number_format((($dailyImpression == 0)? 0 : ($dailyIncome / $dailyImpression) * 1000),2,".",",")  . '</td>
					<td>' . number_format($dailyMedia_cost,2,".",",") . '</td>
					<td>' . number_format((($dailyIncome == 0)? 0 : ($dailyMedia_cost / $dailyIncome)) * 100,2,".",",") . '%</td>
				</tr>	
			</table>
			<br>
			<p>訂單明細</p>						
			';

	  		$html .= '</table>';
		}else{
			$html .= '沒有資料';
		}

		if(isset($_GET['view']) && $_GET['view'] == 1){
			echo $reportTime . $tot . $html; exit;
		}
		

		foreach (Yii::app()->params['alertMailGroup'] as $mail) {
			$this->email($mail, $type . $stime . " - CLICKFORCE EAS AUTO Hourly ", $reportTime . $tot . $html);
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
				foreach (Yii::app()->params['adminMail'] as $mail) {
					$this->email($mail, "CLICKFORCE EAS ALERT : 時報檢查失敗 => DB Error After Retry! (Pc)", $e->getMessage());
				}
				return "DB_ERROR";
			}else{
				sleep(5);
				$model = $this->tryGetHourlyPc($try,$criteria);
			}
				 
		}	
	}

	function tryGetDailyPc($try=0,$criteria){
		try {   
			$model = TosTreporBuyDrDisplayHourlyPcReport::model()->find($criteria);  
			return $model;
		} catch (Exception $e) { 
			$this->writeLog("ERR : (TRY : " . $try ." ) ". $e->getMessage(),"CronHourlyPc/error",date("Ymd") . "errorLog.log");					 
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
				foreach (Yii::app()->params['adminMail'] as $mail) {
					$this->email($mail, "CLICKFORCE EAS ALERT : 時報檢查失敗 => DB Error After Retry ! (Mob)", $e->getMessage());
				}
				return "DB_ERROR";
			}else{
				sleep(5);
				$model = $this->tryGetHourlyPc($try,$criteria);
			}
				 
		}	
	}

	function tryGetDailyMob($try=0,$criteria){
		try {   
			$model = TosTreporBuyDrDisplayHourlyMobReport::model()->find($criteria);  
			return $model;
		} catch (Exception $e) { 
			$this->writeLog("ERR : (TRY : " . $try ." ) ". $e->getMessage(),"CronHourlyPc/error",date("Ymd") . "errorLog.log");			 
		}	
	}


}