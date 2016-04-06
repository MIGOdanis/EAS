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

	public function actionXlxsDaily()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		require dirname(__FILE__).'/../extensions/phpexcel/PHPExcel.php';
		require dirname(__FILE__).'/../extensions/phpexcel/PHPExcel/Writer/Excel2007.php';
		$dailyDay = date("Y-m-d 00:00:00", time() - 86400);
		$dailyTime = strtotime($dailyDay);		
		foreach (Yii::app()->params["autoDaily"] as $value) {
			$model = $this->makeXlxsDaily($value,$dailyDay);
			if(!empty($value["mu"]) && $model != null){
				$this->makeXlxsMuDaily($value,$dailyDay);
			}
		}

	}	

	public function makeXlxsMuDaily($value,$dailyDay)
	{
		$mailto = $value["mu"]["mailto"];
		$model = BuyReportDailyPc::model()->getCampaignDailyByAdvertiserId($value["advertiserId"],32);
		if($model!=null){
			$PHPExcel = PHPExcel_IOFactory::load(dirname(__FILE__) . "/../extensions/xlsxTemp/amnetmuBase.xlsx");
			$sheet = $PHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			foreach ($model as $report) {
				$highestRow++;
				// $PHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $highestRow, date("Y-m-d", $report->settled_time));
				$PHPExcel->setActiveSheetIndex(0)->setCellValueExplicit(
					'A' . $highestRow, date("Y-m-d", $report->settled_time),PHPExcel_Cell_DataType::TYPE_DATE
				);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $highestRow, $report->campaign->advertiser->short_name);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $highestRow, $report->campaign->campaign_name);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $highestRow, $report->impression);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $highestRow, $report->click);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $highestRow, $report->agency_income);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $highestRow, $report->income);
			}
			$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
			$file = dirname(__FILE__).'/../extensions/xlsxTemp/'. date("Ymdhis") . "-" . $value["advertiserId"] .'-m.xlsx';
			$fileMime = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
			$objWriter->save($file);	

			$Source = "web.service@clickforce.com.tw";
			$SourceName = "CLICKFORCE";
			$BccAddresses = $value["mu"]["cc"];
			$Subject = "CLICKFORCE Advertiser M AUTO DAILY " . $dailyDay;
			$Body = "報表時間 : " . $dailyDay . "<br> 域動行銷股份有限公司 <br> tel : 02-2719-8500";

			$mail=SESRaw::sendMail($Source,$SourceName,$mailto,$BccAddresses,$Subject,$Body,$file,$fileMime);
			if($mail['send']){
				$this->writeLog(
					"mailTo[" . $mailto . "] : " . $mail['msg'],
					"MessageMailRaw/run",
					date("Ymd") . "runLog.log"
				);
			}else{
				$this->writeLog(
					"mailTo[" . $mailto . "] : " . $mail['msg'],
					"MessageMailRaw/error",
					date("Ymd") . "errorLog.log"
				);
			}	

			print_r($mail['msg']);						
		}else{
			$this->writeLog(
				"no data" . $value["advertiserId"],
				"xlxsDaily/run",
				date("Ymd") . "errorLog.log"
			);				
		}
	}	

	public function makeXlxsDaily($value,$dailyDay)
	{
		$mailto = $value["mailto"];
		$model = BuyReportDailyPc::model()->getCampaignDailyByAdvertiserId($value["advertiserId"],8);
		if($model!=null){
			$PHPExcel = PHPExcel_IOFactory::load(dirname(__FILE__) . "/../extensions/xlsxTemp/amnetBase.xlsx");
			$sheet = $PHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			foreach ($model as $report) {
				$highestRow++;
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $highestRow, date("Y-m-d", $report->settled_time));
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $highestRow, $report->campaign->advertiser->short_name);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $highestRow, $report->campaign->campaign_name);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $highestRow, $report->impression);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $highestRow, $report->click);
				$PHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $highestRow, $report->agency_income);
			}
			$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
			$file = dirname(__FILE__).'/../extensions/xlsxTemp/'. date("Ymdhis") . "-" . $value["advertiserId"] .'.xlsx';
			$fileMime = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
			$objWriter->save($file);	

			$Source = "web.service@clickforce.com.tw";
			$SourceName = "CLICKFORCE";
			$BccAddresses = $value["cc"];
			$Subject = "CLICKFORCE Advertiser AUTO DAILY " . $dailyDay;
			$Body = "報表時間 : " . $dailyDay . "<br> 域動行銷股份有限公司 <br> tel : 02-2719-8500";

			$mail=SESRaw::sendMail($Source,$SourceName,$mailto,$BccAddresses,$Subject,$Body,$file,$fileMime);
			if($mail['send']){
				$this->writeLog(
					"mailTo[" . $mailto . "] : " . $mail['msg'],
					"MessageMailRaw/run",
					date("Ymd") . "runLog.log"
				);
			}else{
				$this->writeLog(
					"mailTo[" . $mailto . "] : " . $mail['msg'],
					"MessageMailRaw/error",
					date("Ymd") . "errorLog.log"
				);
			}	

			print_r($mail['msg'] . "<BR>");						
		}else{
			$this->writeLog(
				"no data" . $value["advertiserId"],
				"xlxsDaily/run",
				date("Ymd") . "errorLog.log"
			);				
		}

		return $model;		
	}	
	
}