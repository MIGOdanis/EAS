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

	public function actionCategoryReport()
	{	
		if(isset($_GET['export']) && $_GET['export'] == 1){
			$model = BuyReportDailyPc::model()->supplierCategoryReport();

			$day = $this->getDay();

			$data = array();
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
			}

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
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_categoryReport',array(
				'model'=>$model,
			));	
			Yii::app()->end();
		}

		$this->render('categoryReport');
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

	public function getDay(){

		if(!isset($_GET) || $_GET['type'] == "7day"){
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