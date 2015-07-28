<?php

class CronMailController extends Controller
{
	public function actionCronMessageMail()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.cron_mail = 1");
		$criteria->addCondition("t.send_mail = 0");
		$criteria->addCondition("t.active = 1");
		$criteria->addCondition("t.active = 1");
		$criteria->addCondition("t.publish_time <= " . time());
		$criteria->addCondition("t.expire_time >= " . time() . " OR t.expire_time = 0");
		$message = Message::model()->findAll($criteria);
		foreach ($message as $msg) {
			$user_id = explode(":", $msg->user_id);

			$uid = array();
			foreach ($user_id as $key => $value) {
				if(!empty($value)){
					$uid[] = $value;
				}
			}

			$criteria = new CDbCriteria; 
			$criteria->addInCondition("t.id", $uid);
			$user = User::model()->findAll($criteria);

			$setStatus = Message::model()->findByPk($msg->id);
			$setStatus->send_mail = 1;
			$setStatus->save();

			foreach ($user as $key => $value) {
				$Source = Yii::app()->params['mail']['adminEmail'];
				$SourceName = Yii::app()->params['mail']['adminEmailName'];
				$mailto = $value->user;
				$BccAddresses = "";
				$Subject = "訊息通知 : " . $msg->title;
				$Body = file_get_contents(dirname(__FILE__).'/../extensions/wordTemp/mailTemp.html');
				$Body = str_replace ("{body}","您有新訊息<br>".$msg->title."<br>". "您可以<a href='" . Yii::app()->params['baseUrl'] . "'>登入您的後台</a>查看相關訊息",$Body);

				$mail=SES::sendMail($Source,$SourceName,$mailto,$BccAddresses,$Subject,$Body);
				if($mail['send']){
					$this->writeLog(
						"mailTo[" . $mailto . "] : " . $mail['msg'],
						"MessageMail/run",
						date("Ymd") . "runLog.log"
					);
				}else{
					$this->writeLog(
						"mailTo[" . $mailto . "] : " . $mail['msg'],
						"MessageMail/error",
						date("Ymd") . "errorLog.log"
					);
				}		
			}	
		}
	}
}