<?php

class AdvertiserReportController extends Controller
{

	//權限驗證模組
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return $this->checkUserAuth();
	}
	//權限驗證模組

	public function getCampaign($campaignId){

		$criteria=new CDbCriteria;

		if($this->user->group == 8){
			$createrCriteria=new CDbCriteria;
			$createrCriteria->addCondition("id = '" . $this->user->supplier_id . "' OR parent_id = '" . $this->user->supplier_id . "'");
			$creater = TosUpmUser::model()->findAll($createrCriteria);
			$createrArray = array();
			foreach($creater as $value){
				$createrArray[] = $value->id;
			}
			if(is_array($createrArray)){
				$criteria->addInCondition("t.create_user",$createrArray);
			}else{
				$criteria->addCondition("t.create_user = '" . $_GET['creater'] . "'");	
			}
		}

		$criteria->addCondition("tos_id = '" . $campaignId . "'");
		return $model = Campaign::model()->find($criteria);
		
	}

	public function getStrategy($StrategyId){

		$criteria=new CDbCriteria;

		if($this->user->group == 8){
			$createrCriteria=new CDbCriteria;
			$createrCriteria->addCondition("id = '" . $this->user->supplier_id . "' OR parent_id = '" . $this->user->supplier_id . "'");
			$creater = TosUpmUser::model()->findAll($createrCriteria);
			$createrArray = array();
			foreach($creater as $value){
				$createrArray[] = $value->id;
			}
			if(is_array($createrArray)){
				$criteria->addInCondition("campaign.create_user",$createrArray);
			}else{
				$criteria->addCondition("campaign.create_user = '" . $_GET['creater'] . "'");	
			}
		}

		$criteria->addCondition("t.tos_id = '" . $StrategyId . "'");
		return $model = Strategy::model()->with("campaign")->find($criteria);
		
	}
	

	public function actionCategoryReport()
	{	
		
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			$model = BuyReportDailyPc::model()->supplierCategoryReport($_GET['CampaignId']);
	
			$data = array();

			$impression = 0;
			$click = 0;
			$income = 0;				
			foreach ($model as $value) {
				$data[] = array(
					"A" => $value->adSpace->site->category->mediaCategory->name,
					"B" => number_format($value->impression, 0, "." ,""),
					"C" => number_format($value->click, 0, "." ,""),
					"D" => (($value->impression > 0) ? round(($value->click / $value->impression) * 100, 2) : 0) . "%",
					"E" => number_format($value->income, 2, "." ,""),
					"F" => (($value->impression > 0) ? number_format(($value->income / $value->impression) * 1000, 2, "." ,"") : 0),
					"G" => (($value->click > 0) ? number_format(($value->income / $value->click), 2, "." ,"") : 0),
				);
				$impression += $value->impression;
				$click += $value->click;
				$income += $value->income;

			}

			$data[] = array(
				"A" => "合計",
				"B" => number_format($impression, 0, "." ,""),
				"C" => number_format($click, 0, "." ,""),
				"D" => (($impression > 0) ? round(($click / $impression) * 100, 2) : 0) . "%",
				"E" => number_format($income, 2, "." ,""),
				"F" => (($impression > 0) ? number_format(($income / $impression) * 1000, 2, "." ,"") : 0),
				"G" => (($click > 0) ? number_format(($income / $click), 2, "." ,"") : 0),
			);

			$report = array(
				"name" => "訂單類別報表",
				"titleName" => "訂單類別報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "訂單類別報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "G1",
				"title" => array(
					"A2" => "分類",
					"B2" => "曝光",
					"C2" => "點擊",
					"D2" => "點擊率",
					"E2" => "廣告主花費",
					"F2" => "eCPM",
					"G2" => "eCPC",
				),
				"data" => $data
			);	

			$this->exportExcel($report);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			//print_r($_GET['CampaignId']); exit;
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_categoryReport',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('categoryReport');
	}

	public function actionCampaignBannerReport()
	{	
		ini_set('memory_limit', '512M');
		
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			$model = BuyReportDailyPc::model()->campaignBannerReport($_GET['CampaignId']);
	
			$data = array();

			$impression = 0;
			$click = 0;
			$income = 0;				
			foreach ($model as $value) {
				$data[] = array(
					"A" => date("Y-m-d",$value->settled_time),
					"B" => $value->campaign->campaign_name,
					"C" => $value->strategy->strategy_name,
					"D" => $value->creative->creativeGroup->name,
					"E" => $value->width_height,
					"F" => $value->adSpace->site->category->mediaCategory->name,
					"G" => number_format($value->impression, 0, "." ,""),
					"H" => number_format($value->click, 0, "." ,""),
					"I" => (($value->impression > 0) ? round(($value->click / $value->impression) * 100, 2) : 0) . "%",
					"J" => number_format($value->income, 2, "." ,""),
					"K" => (($value->impression > 0) ? number_format(($value->income / $value->impression) * 1000, 2, "." ,"") : 0),
					"L" => (($value->click > 0) ? number_format(($value->income / $value->click), 2, "." ,"") : 0),

				);
				$impression += $value->impression;
				$click += $value->click;
				$income += $value->income;

			}

			$data[] = array(
				"A" => "",
				"B" => "",
				"C" => "",
				"D" => "",
				"E" => "",
				"F" => "合計",
				"G" => number_format($impression, 0, "." ,""),
				"H" => number_format($click, 0, "." ,""),
				"I" => (($impression > 0) ? round(($click / $impression) * 100, 2) : 0) . "%",
				"J" => number_format($income, 2, "." ,""),
				"K" => (($impression > 0) ? number_format(($income / $impression) * 1000, 2, "." ,"") : 0),
				"L" => (($click > 0) ? number_format(($income / $click), 2, "." ,"") : 0),
			);

			$report = array(
				"name" => "廣告活動總表",
				"titleName" => "(" . $campaign->tos_id . ")" . $campaign->campaign_name . " 廣告活動總表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "廣告活動總表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "L1",
				"title" => array(
					"A2" => "日期",
					"B2" => "訂單名稱",
					"C2" => "策略名稱",
					"D2" => "素材名稱",
					"E2" => "尺寸",
					"F2" => "分類",
					"G2" => "曝光",
					"H2" => "點擊", 
					"I2" => "點擊率", 
					"J2" => "廣告主花費", 
					"K2" => "eCPM", 
					"L2" => "eCPC", 
				),
				"data" => $data
			);	

			$this->exportExcel($report);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			//print_r($_GET['CampaignId']); exit;
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_campaignBannerReport',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('campaignBannerReport');
	}

	public function actionYtbReport()
	{	
		
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			$this->exportYtbReport($campaign,$day);
			Yii::app()->end();
		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			//print_r($_GET['CampaignId']); exit;
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_ytbReport',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('ytbReport');
	}

	public function actionStrategyReport()
	{	
		
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$day = $this->getDay();
			$this->exportStrategyReport();
			Yii::app()->end();
		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			$strategy = $this->getStrategy($_GET['StrategyId']);
			//print_r($_GET['CampaignId']); exit;
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_strategyReport',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'strategy'=>$strategy,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('strategyReport');
	}
	public function exportYtbReport($campaign,$day){
		require dirname(__FILE__).'/../extensions/phpexcel/PHPExcel.php';

		$data = BuyReportDailyPc::model()->exportYtbReport($campaign->tos_id);
		$category = BuyReportDailyPc::model()->exportYtbCategoryReport($campaign->tos_id);

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

		$title2 = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'E8C7C7'),
			),
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
		);



		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("CLICKFORCE INC.")->setTitle("CLICKFORCE Supplier Report")
									 ->setSubject("CLICKFORCE Supplier Report")->setCategory("Report");
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "(" . $campaign->tos_id . ")" . $campaign->campaign_name . '影音報表 - 每日成效報表(' .$day[0] . " - " .$day[1] . ')');

		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->applyFromArray($reportName);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('B2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->applyFromArray($title);

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '日期');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '曝光');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '有效觀看數');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', '導頁連結');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', '加值功能');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', '有效VTR');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', '100%收視數');

		$r = 3;
		foreach ($data as $value) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$r, date("Y-m-d",$value['settled_time']));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$r, $value["data"]->impression);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$r, (int)$value["ytbReport"]["totView"]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$r, $value["data"]->click);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$r, (int)$value['functionReport']["totClick"]);
			
			if((int)$value["data"]->impression > 0){
				$Vcount = (int)$value["ytbReport"]["totView"] + (int)$value["data"]->click + (int)$value['functionReport']["totClick"];
				$vtr = ($Vcount / (int)$value["data"]->impression) * 100;
			}else{
				$vtr = 0;
			}

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$r, number_format($vtr , 2, "." ,","). "%");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$r, (int)$value["ytbReport"]["100"]);			
			$r++;
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$r, "資料時間" . date("Y-m-d H:i:s"));	

		$objPHPExcel->setActiveSheetIndex(0)->setTitle("每日成效報告");


		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:E1');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A1', "(" . $campaign->tos_id . ")" . $campaign->campaign_name . '影音報表 - 分類成效報告(' .$day[0] . " - " .$day[1] . ')');
		$objPHPExcel->setActiveSheetIndex(1)->setTitle("分類成效報告");
		$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1')->applyFromArray($reportName);
		$objPHPExcel->setActiveSheetIndex(1)->getStyle('A2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(1)->getStyle('B2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(1)->getStyle('C2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(1)->getStyle('D2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(1)->getStyle('E2')->applyFromArray($title);
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A2', '日期');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B2', '曝光');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C2', '有效觀看數');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D2', '導頁連結');
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E2', '100%收視數');

		$r = 3;
		foreach ($category as $value) {
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A'.$r, $value['data']->adSpace->site->category->mediaCategory->name);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B'.$r, $value["data"]->impression);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C'.$r, (int)$value['ytb']['totView']);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D'.$r, $value["data"]->click);
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E'.$r, (int)$value['ytb']['100']);
					
			$r++;
		}
		$objPHPExcel->setActiveSheetIndex(1)->setCellValue("A".$r, "資料時間" . date("Y-m-d H:i:s"));	



		$objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' .$day[0] . "-" .$day[1] . '影音報表.xlsx"');
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


	public function actionFunctionReport()
	{	
		
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			$model = BuyReportDailyPc::model()->functionReport($_GET['CampaignId']);
	
			$data = array();

			$impression = 0;
			$click = 0;
			$income = 0;				
			foreach ($model as $value) {
				$data[] = array(
					"A" => date("Y-m-d",$value["settled_time"]),
					"B" => $value["campaign"]->campaign_name,
					"C" => $value["creative"]->creativeGroup->name,
					"D" => number_format($value["data"]->impression, 0, "." ,","),
					"E" => number_format($value["data"]->click, 0, "." ,","),
					"F" => (($value["data"]->impression > 0) ? round(($value["data"]->click / $value["data"]->impression) * 100, 2) : 0) . "%",
					"G" => $value["temp_table"]["functionName"],
					"H" => $value["temp_table"]["totClick"],

				);
			}

			$report = array(
				"name" => "加值功能報表",
				"titleName" => "(" . $campaign->tos_id . ")" . $campaign->campaign_name . " 加值功能報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "加值功能報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "H1",
				"title" => array(
					"A2" => "日期",
					"B2" => "訂單名稱",
					"C2" => "素材名稱",
					"D2" => "曝光",
					"E2" => "點擊",
					"F2" => "點擊率",
					"G2" => "加值功能",
					"H2" => "加值功能點擊"
				),
				"data" => $data
			);	

			$this->exportExcel($report);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_functionReport',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('functionReport');
	}


	public function exportStrategyReport()
	{	
		
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$model = BuyReportDailyPc::model()->strategyReport($_GET['CampaignId'],$_GET['StrategyId']);
	
			$data = array();

			$impression = 0;
			$click = 0;
			$income = 0;				
			foreach ($model as $value) {
				$data[] = array(
					"A" => $value->campaign_id,
					"B" => $value->campaign->campaign_name,
					"C" => $value->strategy_id,
					"D" => $value->strategy->strategy_name,
					"E" => number_format($value->impression, 0, "." ,""),
					"F" => number_format($value->click, 0, "." ,""),
					"G" => (($value->impression > 0) ? round(($value->click / $value->impression) * 100, 2) : 0) . "%",
					"H" => number_format($value->income, 2, "." ,""),
					"I" => (($value->impression > 0) ? number_format(($value->income / $value->impression) * 1000, 2, "." ,"") : 0),
					"J" => (($value->click > 0) ? number_format(($value->income / $value->click), 2, "." ,"") : 0),
				);
			}

			$report = array(
				"name" => "素材報表",
				"titleName" => "(" . $campaign->tos_id . ")" . $campaign->campaign_name . " 素材報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "素材報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "J1",
				"title" => array(
					"A2" => "訂單編號",
					"B2" => "訂單名稱",
					"C2" => "策略編號",
					"D2" => "策略名稱",
					"E2" => "曝光",
					"F2" => "點擊",
					"G2" => "點擊率",
					"H2" => "廣告主花費",					
					"I2" => "eCPC",
					"J2" => "eCPM"
				),
				"data" => $data
			);	

			$this->exportExcel($report);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$day = $this->getDay();
			$campaign = $this->getCampaign($_GET['CampaignId']);
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_functionReport',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('functionReport');
	}
}