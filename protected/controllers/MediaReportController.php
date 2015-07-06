<?php

class MediaReportController extends Controller
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
	
	public function actionSupplierReport()
	{	
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$adSpacArray = $this->getSpaceArray();
			$day = $this->getDay();

			$model = BuyReportDailyPc::model()->adminSupplierDailyReport($adSpacArray['adSpacArray']);

			$data = array();
			foreach ($model as $value) {
				$data[] = array(
					"A" => $value->adSpace->site->supplier->name,
					"B" => number_format($value->impression, 0, "." ,""),
					"C" => number_format($value->click, 0, "." ,""),
					"D" => (($value->impression > 0) ? round(($value->click / $value->impression) * 100, 2) : 0) . "%",
					"E" => number_format($value->media_cost, 2, "." ,""),
					"F" => (($value->impression > 0) ? number_format(($value->media_cost / $value->impression) * 1000, 2, "." ,"") : 0),
					"G" => (($value->click > 0) ? number_format(($value->media_cost / $value->click), 2, "." ,"") : 0),
				);
			}

			$report = array(
				"name" => "供應商日報表",
				"titleName" => "供應商日報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "供應商日報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "G1",
				"title" => array(
					"A2" => "供應商",
					"B2" => "曝光",
					"C2" => "點擊",
					"D2" => "點擊率",
					"E2" => "媒體成本",
					"F2" => "eCPM",
					"G2" => "eCPC",
				),
				"data" => $data
			);	

			$this->exportExcel($report);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$adSpacArray = $this->getSpaceArray();
			$day = $this->getDay();

			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_supplierReport',array(
				'model'=>$model,
				'day' => $day,
				'adSpacArray' => $adSpacArray['adSpacArray'],
				'supplier' => $adSpacArray['supplier'],
			));	
			Yii::app()->end();
		}

		$this->render('supplierReport');
	}

	public function actionSiteReport()
	{	
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$adSpacArray = $this->getSpaceArray();
			$day = $this->getDay();			
			$model = BuyReportDailyPc::model()->adminSupplierDailyReport($adSpacArray['adSpacArray']);

			$data = array();
			foreach ($model as $value) {
				$data[] = array(
					"A" => $value->adSpace->site->name,
					"B" => number_format($value->impression, 0, "." ,""),
					"C" => number_format($value->click, 0, "." ,""),
					"D" => (($value->impression > 0) ? round(($value->click / $value->impression) * 100, 2) : 0) . "%",
					"E" => number_format($value->media_cost, 2, "." ,""),
					"F" => (($value->impression > 0) ? number_format(($value->media_cost / $value->impression) * 1000, 2, "." ,"") : 0),
					"G" => (($value->click > 0) ? number_format(($value->media_cost / $value->click), 2, "." ,"") : 0),
				);
			}

			$report = array(
				"name" => "供應商網站日報表",
				"titleName" => "供應商網站日報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "供應商網站日報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "G1",
				"title" => array(
					"A2" => "網站",
					"B2" => "曝光",
					"C2" => "點擊",
					"D2" => "點擊率",
					"E2" => "媒體成本",
					"F2" => "eCPM",
					"G2" => "eCPC",
				),
				"data" => $data
			);	

			$this->exportExcel($report);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$adSpacArray = $this->getSpaceArray();
			$day = $this->getDay();		

			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_siteReport',array(
				'model'=>$model,
				'day' => $day,
				'adSpacArray' => $adSpacArray['adSpacArray'],
				'supplier' => $adSpacArray['supplier'],				
			));	

			Yii::app()->end();
		}

		$this->render('siteReport');
	}

	public function actionAdSpaceReport()
	{	
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$adSpacArray = $this->getSpaceArray();
			$day = $this->getDay();					
			$model = BuyReportDailyPc::model()->adminSupplierDailyReport($adSpacArray['adSpacArray']);

			$data = array();
			foreach ($model as $value) {
				$data[] = array(
					"A" => $value->adSpace->name,
					"B" => number_format($value->impression, 0, "." ,""),
					"C" => number_format($value->click, 0, "." ,""),
					"D" => (($value->impression > 0) ? round(($value->click / $value->impression) * 100, 2) : 0) . "%",
					"E" => number_format($value->media_cost, 2, "." ,""),
					"F" => (($value->impression > 0) ? number_format(($value->media_cost / $value->impression) * 1000, 2, "." ,"") : 0),
					"G" => (($value->click > 0) ? number_format(($value->media_cost / $value->click), 2, "." ,"") : 0),
				);
			}

			$report = array(
				"name" => "供應商網站日報表",
				"titleName" => "供應商網站日報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "供應商網站日報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "G1",
				"title" => array(
					"A2" => "版位",
					"B2" => "曝光",
					"C2" => "點擊",
					"D2" => "點擊率",
					"E2" => "媒體成本",
					"F2" => "eCPM",
					"G2" => "eCPC",
				),
				"data" => $data
			);	

			$this->exportExcel($report);
			Yii::app()->end();	

		}
		if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
			$adSpacArray = $this->getSpaceArray();
			$day = $this->getDay();		

			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_adSpaceReport',array(
				'model'=>$model,
				'day' => $day,
				'adSpacArray' => $adSpacArray['adSpacArray'],
				'supplier' => $adSpacArray['supplier'],					
			));	

			Yii::app()->end();
		}

		$this->render('adSpaceReport');
	}

	// 報表資料陣列格式
	// array(
	// 	"name" => "報表名稱",
	// "titleName" => "訂單類別報表 查詢時間" . $day[0] . "~" . $day[1],
	// "fileName" => "訂單類別報表 查詢時間" . $day[0] . "~" . $day[1],
	// 	"width" => "B1",
	// 	"title" => array(
	// 		"A1" => "標題",
	// 		"B1" => "標題",
	// 	),
	// 	"data" => array(
	// 		array(
	// 			"A" => "內容",
	// 			"B" => "內容",
	// 		),
	// 		array(
	// 			"A" => "內容",
	// 			"B" => "內容",
	// 		),
	// 	)
	// )
	public function getSpaceArray(){
		$adSpacArray = array();	
		if( (isset($_GET['supplierId']) && !empty($_GET['supplierId'])) || (isset($_GET['siteId']) && !empty($_GET['siteId']))  || (isset($_GET['adSpaceId']) && $_GET['adSpaceId'] > 0) ){

			$criteria=new CDbCriteria;

			if(isset($_GET['supplierId']) && !empty($_GET['supplierId']))
				$criteria->addCondition("t.tos_id = '" . $_GET['supplierId'] . "' OR t.name LIKE '%" . $_GET['supplierId'] . "%'");		
		
			if(isset($_GET['siteId']) && !empty($_GET['siteId']))
				$criteria->addCondition("site.tos_id = '" . $_GET['siteId'] . "' OR site.name LIKE '%" . $_GET['siteId'] . "%'");	
			
			if(isset($_GET['adSpaceId']) && $_GET['adSpaceId']  > 0)
				$criteria->addCondition("adSpace.tos_id = '" . $_GET['adSpaceId'] . "' OR adSpace.name LIKE '%" . $_GET['adSpaceId'] . "%'");	
			
			$supplier = Supplier::model()->with("site","site.adSpace")->find($criteria);
			
			if($supplier !== null){
				foreach ($supplier->site as $site) {
					foreach ($site->adSpace as $value) {
						$adSpacArray[] = $value->tos_id;
					}
				}				
			}
		}

		return array("adSpacArray" => $adSpacArray, "supplier" => $supplier);		
	}
	public function getDay(){

		if(!isset($_GET) || $_GET['type'] == "yesterday"){
			$startDay = date("Y-m-d",strtotime('-1 day'));
			$endDay = date("Y-m-d",strtotime('-1 day')); 
		}


		if($_GET['type'] == "7day"){
			$startDay = date("Y-m-d",strtotime('-7 day'));
			$endDay = date("Y-m-d"); 
		}

		if($_GET['type'] == "30day"){
			$startDay = date("Y-m-d",strtotime('-30 day'));
			$endDay = date("Y-m-d"); 			
		}

		if($_GET['type'] == "pastMonth"){
			$startDay = date("Y-m-01",strtotime("-1 Months"));
			$endDay = date("Y-m-t",strtotime("-1 Months")); 			
		}

		if($_GET['type'] == "thisMonth"){
			$startDay = date("Y-m-01");
			$endDay = date("Y-m-t"); 	
		}	

		if($_GET['type'] == "custom"){
			if(isset($_GET['startDay']) && !empty($_GET['startDay'])){
				$startDay = date("Y-m-d",strtotime($_GET['startDay'] . "00:00:00"));
			}
			if(isset($_GET['endDay']) &&  !empty($_GET['endDay'])){
				$endDay = date("Y-m-d",strtotime($_GET['endDay'] . "00:00:00"));
			}			
		}

		return array($startDay,$endDay);
	}

	


}