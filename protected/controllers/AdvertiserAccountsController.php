<?php

class AdvertiserAccountsController extends Controller
{
	// //權限驗證模組
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 		'postOnly + delete', // we only allow deletion via POST request
	// 	);
	// }

	// public function accessRules()
	// {
	// 	return $this->checkUserAuth();
	// }
	// //權限驗證模組
	
	public function actionAdmin()
	{
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$model = BuyReportDailyPc::model()->advertiserAccountsReport($_GET['CampaignId']);
			$this->exportExcelAA($model);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$day = $this->getDay();
			if(isset($_GET['creater']) && ($_GET['creater'] > 0))
				$creater = TosUpmUser::model()->findByPk((int)$_GET['creater']);
			//$campaign = $this->getCampaign($_GET['CampaignId']);
			//print_r($_GET['CampaignId']); exit;
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_report',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day,
				'creater' => $creater

			));	
			Yii::app()->end();
		}

		$criteria=new CDbCriteria;
		$criteria->addCondition("account_id = 2");
		$creater = TosUpmUser::model()->findAll($criteria);

		$this->render('admin',array(
			"creater" => $creater,
		));
	}

	public function actionSelectBelong($id)
	{				
		$criteria=new CDbCriteria;
		$criteria->addCondition("t.tos_id = '" . $id . "'");
		$campaign = Campaign::model()->with("belong")->find($criteria);

		$criteria=new CDbCriteria;
		$criteria->addCondition("`group` = 5");
		$user = User::model()->findAll($criteria);

		if(isset($_POST['uid'])){
			header('Content-type: application/json');
			$campaign->belong_by = $_POST['uid'];
			if($campaign->save()){
				echo json_encode(array("code" => "1"));
			}else{
				echo json_encode(array("code" => "2"));
			}
			Yii::app()->end();
		}

		$this->renderPartial('selectBelong',array(
			"model" => $model,
			"user" => $user
		));
	}

	public function actionSelectActive($id)
	{				
		$criteria=new CDbCriteria;
		$criteria->addCondition("t.tos_id = '" . $id . "'");
		$model = Campaign::model()->find($criteria);

		if(isset($_POST['closePrice'])){
			header('Content-type: application/json');
			$model->close_price = $_POST['closePrice'];
			$model->active = 0;
			if($model->save()){
				echo json_encode(array("code" => "1"));
			}else{
				echo json_encode(array("code" => "2"));
			}
			Yii::app()->end();
		}


		if(isset($_POST['reset'])){
			header('Content-type: application/json');
			$model->close_price = 0;
			$model->active = 1;
			if($model->save()){
				echo json_encode(array("code" => "1"));
			}else{
				echo json_encode(array("code" => "2"));
			}
			Yii::app()->end();
		}


		// if($campaign !== null){
		// 	header('Content-type: application/json');
		// 	$campaign->active = ($campaign->active == 1) ? 0 : 1;
		// 	if($campaign->save()){
		// 		echo json_encode(array("code" => "1"));
		// 	}else{
		// 		echo json_encode(array("code" => "2"));
		// 	}
		// 	Yii::app()->end();			
		// }

		$this->renderPartial('selectActive',array(
			"model" => $model,
		));

	}

	public function actionGetDefindReceivables()
	{				
		$criteria=new CDbCriteria;
		$criteria->select = 'sum(t.income) / 100000 as income';		
		$criteria->addCondition("settled_time >= " . strtotime($_GET['Y'] . "-" . $_GET['M'] . "-01" . " 00:00:00"));
		$criteria->addCondition("settled_time <= " . strtotime(date("Y-m-t 00:00:00", strtotime($_GET['Y'] . "-" . $_GET['M'] . "-01"))));
		$criteria->addCondition("t.campaign_id = '" . $_GET['CampaignId'] . "'");
		$model = BuyReportDailyPc::model()->find($criteria);
		// print_r($model); exit;
		header('Content-type: application/json');
		echo json_encode(array("income" => $model->income));
		Yii::app()->end();
	}

	public function actionCreatReceivables($id)
	{				
		$model = new AdvertiserReceivables();

		$criteria=new CDbCriteria;
		$criteria->addCondition("t.tos_id = '" . $id . "'");
		$campaign = Campaign::model()->with("budget")->find($criteria);

		$allIncome = BuyReportDailyPc::model()->getCampaignAllIncome($id);
		$advertiserReceivables = AdvertiserReceivables::model()->getCampaignAdvertiserReceivables($id);


		if(isset($_POST['AdvertiserReceivables'])){
			header('Content-type: application/json');
			$model->campaign_id = $id;
			$model->year = $_POST['year'];
			$model->month = $_POST['month'];
			$model->price = $_POST['price'];
			$model->remark = $_POST['remark'];
			$model->create_time = time();
			$model->create_by =  Yii::app()->user->id;
			$model->active = 1;
			if($model->save()){
				echo json_encode(array("code" => "1"));
			}else{
				echo json_encode(array("code" => "2"));			
			}
			Yii::app()->end();
		}

		$this->renderPartial('creatReceivables',array(
			"model" => $model,
			"campaign" => $campaign,
			"allIncome" => $allIncome,
			"advertiserReceivables" => $advertiserReceivables,
		));
	}


	public function actionDelReceivables($id)
	{	
		if(isset($id)){
			header('Content-type: application/json');
			$model = AdvertiserReceivables::model()->findByPk($id);
			if($model !== null){
				$model->active = 0;
				if($model->save()){
					echo json_encode(array("code" => "1"));
				}else{
					echo json_encode(array("code" => "2"));
				}
			}else{
				echo json_encode(array("code" => "3"));
			}
		}else{
			echo json_encode(array("code" => "4"));
		}

		Yii::app()->end();
	}

	public function actionCreatInvoice($id)
	{				
		$model = new AdvertiserInvoice();

		$criteria=new CDbCriteria;
		$criteria->addCondition("t.tos_id = '" . $id . "'");
		$campaign = Campaign::model()->with("budget")->find($criteria);

		$allIncome = BuyReportDailyPc::model()->getCampaignAllIncome($id);
		$advertiserInvoice = BuyReportDailyPc::model()->getCampaignAdvertiserInvoice($id);


		if(isset($_POST['AdvertiserInvoice'])){
			header('Content-type: application/json');
			$model->campaign_id = $id;
			$model->number = $_POST['number'];
			$model->price = $_POST['price'];
			$model->remark = $_POST['remark'];
			$model->time = strtotime($_POST['time']);
			$model->create_time = time();
			$model->create_by =  Yii::app()->user->id;
			$model->active = 1;
			if($model->save()){
				echo json_encode(array("code" => "1"));
			}else{
				echo json_encode(array("code" => "2"));			
			}
			Yii::app()->end();
		}

		$this->renderPartial('creatInvoice',array(
			"model" => $model,
			"campaign" => $campaign,
			"allIncome" => $allIncome,
			"advertiserInvoice" => $advertiserInvoice,
		));
	}


	public function actionDelInvoice($id)
	{	
		if(isset($id)){
			header('Content-type: application/json');
			$model = AdvertiserInvoice::model()->findByPk($id);
			if($model !== null){
				$model->active = 0;
				if($model->save()){
					echo json_encode(array("code" => "1"));
				}else{
					echo json_encode(array("code" => "2"));
				}
			}else{
				echo json_encode(array("code" => "3"));
			}
		}else{
			echo json_encode(array("code" => "4"));
		}

		Yii::app()->end();
	}

	public function exportExcelAA($model){
		require dirname(__FILE__).'/../extensions/phpexcel/PHPExcel.php';

		$day = $this->getDay();

		$reportName = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'E86D4B'),
			),
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
		);

		$title = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'E8BE93'),
			),
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
		);

		$text = array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
		);

		 

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("CLICKFORCE INC.")->setTitle("CLICKFORCE Supplier Report")
									 ->setSubject("CLICKFORCE Supplier Report")->setCategory("Report");

		$objPHPExcel->getActiveSheet()->mergeCells('A1:AE1');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "經銷對帳查詢");
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->applyFromArray($reportName);

		$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2","查詢走期 : " . implode(" ~ ", $day) );

		$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A3","資料時間 : " . date("Y-m-d H:i:s") );

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A4","發票抬頭");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B4","統一編號");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C4","訂單編號");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D4","訂單名稱");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E4","訂單金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F4","目標曝光");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G4","目標點擊");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H4","CPM");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I4","CPC");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J4","走期(開始日)");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K4","走期(結束日)");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L4","查詢走期曝光");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M4","查詢走期點擊");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("N4","查詢走期執行金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O4","查詢走期認列金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P4","已執行曝光");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q4","已執行點擊");		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("R4","已執行金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S4","尚未執行金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T4","可請款金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("U4","已開發票金額總計");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("V4","未請款金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("W4","發票日期");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("X4","發票金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Y4","發票號碼");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Z4","發票備註");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AA4","建單帳號");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AB4","訂單業務");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AC4","結案狀態");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AD4","結案金額");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AE4","總認列金額");

		$objPHPExcel->setActiveSheetIndex(0)->getStyle("A4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("B4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("C4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("D4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("E4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("F4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("G4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("H4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("I4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("J4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("K4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("L4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("M4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("N4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("O4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("P4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("Q4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("R4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("S4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("T4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("U4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("V4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("W4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("X4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("Y4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("Z4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("AA4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("AB4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("AC4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("AD4")->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle("AE4")->applyFromArray($title);
		$r = 5;

		if($model === null){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $r, "沒有資料");
		}else{
			foreach ($model as $data) {
				$criteria=new CDbCriteria;		
				$criteria->addCondition("t.campaign_id = '" . $data->campaign_id . "'");
				$invoice = AdvertiserInvoice::model()->findAll($criteria);

				$advertiserReceivablesByDay = BuyReportDailyPc::model()->getCampaignAdvertiserReceivables($data->campaign_id);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $r, $data->campaign->advertiser->advertiser_name);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $r, "統一編號");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $r, $data->campaign_id);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $r, $data->campaign->campaign_name);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $r, ($data->budget->total_budget / 100));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $r, (($data->budget->total_pv > 0) ? $data->budget->total_pv : "-"));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $r, (($data->budget->total_click > 0) ? $data->budget->total_click : "-"));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $r, (($data->budget->total_pv > 0) ? round(($data->budget->total_budget / 100) / $data->budget->total_pv,2) : "-"));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $r, (($data->budget->total_click > 0) ? round(($data->budget->total_budget / 100) / $data->budget->total_click,2) : "-"));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $r, date("Y-m-d", $data->campaign->start_time));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $r, date("Y-m-d", $data->campaign->end_time));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $r, (($data->impression_sum > 0) ? $data->impression_sum : "-"));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $r, (($data->click_sum > 0) ? $data->click_sum : "-"));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . $r, number_format($data->income_sum, 0, "" ,""));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . $r, number_format($advertiserReceivablesByDay, 0, "" ,""));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . $r, $data->getCampaignAllIC($data->campaign_id));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q" . $r, $data->temp_click_sum);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("R" . $r, number_format($data->getCampaignAllIncome($data->campaign_id), 0, "" ,""));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S" . $r, number_format(($data->budget->total_budget / 100) - $data->temp_income_sum, 0, "" ,""));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("T" . $r, number_format(($data->temp_income_sum > ($data->budget->total_budget / 100))? ($data->budget->total_budget / 100) : $data->temp_income_sum, 0, "" ,""));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("U" . $r, number_format($data->getCampaignAdvertiserInvoice($data->campaign_id), 0, "" ,""));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("V" . $r, number_format(($data->temp_income_sum > ($data->budget->total_budget / 100))? ($data->budget->total_budget / 100) - $data->temp_advertiser_invoice_sum : $data->temp_income_sum - $data->temp_advertiser_invoice_sum, 0, "" ,""));
			
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AA" . $r, $data->campaign->upm->real_name);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AB" . $r, ($data->campaign->belong_by > 0)? $data->campaign->belong->name : "未填寫");
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AC" . $r, ($data->campaign->active == 0)? "已結案" : "未結案");			
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AD" . $r, ($data->campaign->active == 0)? number_format($data->campaign->close_price, 0, "" ,"") : "未結案");	
				
				$advertiserReceivables = AdvertiserReceivables::model()->getCampaignAdvertiserReceivables($data->campaign_id);

				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("AE" . $r, number_format($advertiserReceivables, 0, "" ,""));	

				if(count($invoice) == 0){
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue("W" . $r, "無發票");
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue("X" . $r, "無發票");
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Y" . $r, "無發票");
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Z" . $r, "無發票");
				}else{
					$count = count($invoice);
					$objPHPExcel->getActiveSheet()->mergeCells("A" . $r . ":A" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("B" . $r . ":B" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("C" . $r . ":C" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("D" . $r . ":D" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("E" . $r . ":E" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("F" . $r . ":F" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("G" . $r . ":G" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("H" . $r . ":H" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("I" . $r . ":I" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("J" . $r . ":J" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("K" . $r . ":K" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("L" . $r . ":L" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("M" . $r . ":M" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("N" . $r . ":N" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("O" . $r . ":O" . ($r + $count));					
					$objPHPExcel->getActiveSheet()->mergeCells("P" . $r . ":P" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("Q" . $r . ":Q" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("R" . $r . ":R" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("S" . $r . ":S" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("T" . $r . ":T" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("U" . $r . ":U" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("V" . $r . ":V" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("AA" . $r . ":AA" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("AB" . $r . ":AB" . ($r + $count));
					$objPHPExcel->getActiveSheet()->mergeCells("AC" . $r . ":AC" . ($r + $count));		
					$objPHPExcel->getActiveSheet()->mergeCells("AD" . $r . ":AD" . ($r + $count));		
					$objPHPExcel->getActiveSheet()->mergeCells("AE" . $r . ":AE" . ($r + $count));

					$objPHPExcel->setActiveSheetIndex(0)->getStyle("A" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("B" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("C" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("D" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("E" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("F" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("G" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("H" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("I" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("J" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("K" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("L" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("M" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("N" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("O" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("P" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("Q" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("R" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("S" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("T" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("U" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("V" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("AA" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("AB" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("AC" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("AD" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$objPHPExcel->setActiveSheetIndex(0)->getStyle("AE" . $r)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$price = 0;
					foreach ($invoice as $value) {	
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("W" . $r, date("Y-m-d",$value->time));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("X" . $r, $value->price);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Y" . $r, $value->number . "(" . (($value->active == 0)? "註銷" : "有效") . ")");
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Z" . $r, $value->remark);					
						if($value->active == 1){
							$price += $value->price;
						}
						$r++;
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue("W" . $r, "總計");
					$objPHPExcel->getActiveSheet()->mergeCells("W" . $r . ":X" . $r);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Y" . $r, $price);
					$objPHPExcel->getActiveSheet()->mergeCells("Y" . $r . ":Z" . $r);
				}
				$r++;
			}      


		}


		$objPHPExcel->getActiveSheet()->setTitle("經銷對帳查詢");
        $objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="經銷對帳查詢' . date("Ymd-his") . '.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}	
}