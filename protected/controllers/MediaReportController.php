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

			$impression = 0;
			$click = 0;
			$media_cost = 0;

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
				$impression += $value->impression;
				$click += $value->click;
				$media_cost += $value->media_cost;

			}

			$data[] = array(
				"A" => "合計",
				"B" => number_format($impression, 0, "." ,""),
				"C" => number_format($click, 0, "." ,""),
				"D" => (($impression > 0) ? round(($click / $impression) * 100, 2) : 0) . "%",
				"E" => number_format($media_cost, 2, "." ,""),
				"F" => (($impression > 0) ? number_format(($media_cost / $impression) * 1000, 2, "." ,"") : 0),
				"G" => (($click > 0) ? number_format(($media_cost / $click), 2, "." ,"") : 0),
			);			

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
			$model = BuyReportDailyPc::model()->adminSiteDailyReport($adSpacArray['adSpacArray']);

			$data = array();

			$impression = 0;
			$click = 0;
			$media_cost = 0;
			foreach ($model as $value) {
				$data[] = array(
					"A" => $value->adSpace->site->name,
					"B" => $value->adSpace->site->category->mediaCategory->name,
					"C" => number_format($value->impression, 0, "." ,""),
					"D" => number_format($value->click, 0, "." ,""),
					"E" => (($value->impression > 0) ? round(($value->click / $value->impression) * 100, 2) : 0) . "%",
					"F" => number_format($value->media_cost, 2, "." ,""),
					"G" => (($value->impression > 0) ? number_format(($value->media_cost / $value->impression) * 1000, 2, "." ,"") : 0),
					"H" => (($value->click > 0) ? number_format(($value->media_cost / $value->click), 2, "." ,"") : 0), 
				);
				$impression += $value->impression;
				$click += $value->click;
				$media_cost += $value->media_cost;

			}

			$data[] = array(
				"A" => "合計",
				"B" => "",
				"C" => number_format($impression, 0, "." ,""),
				"D" => number_format($click, 0, "." ,""),
				"E" => (($impression > 0) ? round(($click / $impression) * 100, 2) : 0) . "%",
				"F" => number_format($media_cost, 2, "." ,""),
				"G" => (($impression > 0) ? number_format(($media_cost / $impression) * 1000, 2, "." ,"") : 0),
				"H" => (($click > 0) ? number_format(($media_cost / $click), 2, "." ,"") : 0),
			);

			$report = array(
				"name" => "供應商網站日報表",
				"titleName" => "供應商網站日報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "供應商網站日報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "H1",
				"title" => array(
					"A2" => "網站",
					"B2" => "網站類別",
					"C2" => "曝光",
					"D2" => "點擊",
					"E2" => "點擊率",
					"F2" => "媒體成本",
					"G2" => "eCPM",
					"H2" => "eCPC",
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
			$model = BuyReportDailyPc::model()->adminAdSpaceDailyReport($adSpacArray['adSpacArray']);

			$data = array();

			$impression = 0;
			$click = 0;
			$media_cost = 0;			
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
				$impression += $value->impression;
				$click += $value->click;
				$media_cost += $value->media_cost;

			}

			$data[] = array(
				"A" => "合計",
				"B" => number_format($impression, 0, "." ,""),
				"C" => number_format($click, 0, "." ,""),
				"D" => (($impression > 0) ? round(($click / $impression) * 100, 2) : 0) . "%",
				"E" => number_format($media_cost, 2, "." ,""),
				"F" => (($impression > 0) ? number_format(($media_cost / $impression) * 1000, 2, "." ,"") : 0),
				"G" => (($click > 0) ? number_format(($media_cost / $click), 2, "." ,"") : 0),
			);

			$report = array(
				"name" => "供應商版位日報表",
				"titleName" => "供應商版位日報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "供應商版位日報表 查詢時間" . $day[0] . "~" . $day[1],
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
}