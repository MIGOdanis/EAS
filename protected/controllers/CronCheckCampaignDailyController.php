<?php

class CronCheckCampaignDailyController extends Controller
{
	public function actionCheck()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$array = array("100606411","100582275","100529903");
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.date = '" . date("Y-m-d 00:00:00") . "'");
		$criteria->addInCondition("campaign",$array);
		$model = TosTserverCampaignDailyHit::model()->findAll($criteria);
		foreach ($model as $value) {
			$campaign = TosCoreWriteCampaign::model()->with("budget")->findByPk($value->campaign);
			if($campaign != null){
				if($campaign->status == 1){
					$hit = ($value->cost / 100);
					$budget = ($campaign->budget->max_daily_budget / 100);
					if($budget > 0){
						$percent = ($hit / $budget);
						$percent = round(($percent * 100),1);
						if($percent > 95){
							$msg .= $campaign->id . " | " . $campaign->campaign_name . " | 控量表花費" . round($hit,0) . " | 訂單預算" . round($budget,0) . " | 超量" . $percent ."% | 關閉時間 : " . date("Y-m-d H:i:s") ."<br>";
						}
					}
				}
			}
		}

		if(!empty($msg)){
			$Source = Yii::app()->params['mail']['adminEmail'];
			$SourceName = Yii::app()->params['mail']['adminEmailName'];
			$mailto = "aws@clickforce.com.tw";
			$BccAddresses = "";
			$Subject = "訂單檢查通知 (關閉) : " . date("Y-m-d");
			$mail=SES::sendMail($Source,$SourceName,$mailto,$BccAddresses,$Subject,$msg);
			print_r($mail);	
		}	

		echo $msg;

	}

	public function closeCampaign($model)
	{
		$lastTime = date("Y-m-d h:i:s");
		$model->status = 2;
		$model->last_changed = $lastTime;
		$model->last_changed_user = 111;
		if($model->save()){
			$log = new CheckHitLog();
			$log->campaign_id = $model->id;
			$log->date = strtotime(date("Y-m-d 00:00:00"));
			$log->update_time = strtotime($lastTime);
			$log->status = 2;
			if(!$log->save()){
				print_r($log->getErrors());
			}
		}

		return false;
	}
	

	public function actionReOpen()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.date = '" . strtotime(date("Y-m-d 00:00:00", time() - 86400)) . "'");
		$criteria->addCondition("t.status = 2");
		$model = CheckHitLog::model()->findAll($criteria);

		foreach ($model as $value) {
			$campaign = TosCoreWriteCampaign::model()->with("budget")->findByPk($value->campaign_id);
			if($campaign != null){
				$endTime = strtotime($campaign->end_time);
				if($endTime > time() && $campaign->last_changed_user == 111){
					$this->openCampaign($campaign);
					$msg .= $campaign->id . " | " . $campaign->campaign_name . " | 訂單重新開啟 <br>";
				}
			}
		}

		if(!empty($msg)){
			$Source = Yii::app()->params['mail']['adminEmail'];
			$SourceName = Yii::app()->params['mail']['adminEmailName'];
			$mailto = "aws@clickforce.com.tw";
			$BccAddresses = "";
			$Subject = "訂單檢查通知 (開啟) : " . date("Y-m-d");
			$mail=SES::sendMail($Source,$SourceName,$mailto,$BccAddresses,$Subject,$msg);			
		}

		echo $msg;

	}


	public function openCampaign($model)
	{
		$lastTime = date("Y-m-d h:i:s");
		$model->status = 1;
		$model->last_changed = $lastTime;
		$model->last_changed_user = 111;
		if($model->save()){
			$log = new CheckHitLog();
			$log->campaign_id = $model->id;
			$log->date = strtotime(date("Y-m-d 00:00:00"));
			$log->update_time = strtotime($lastTime);
			$log->status = 1;
			if(!$log->save()){
				print_r($log->getErrors());
			}
		}

		return false;
	}
}