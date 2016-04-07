<?php

class MediaHourlyReportController extends Controller
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
	
	public function actionSupplierHourlyReport()
	{	
		if((isset($_GET['ajax']) && $_GET['ajax'] == 1) || (isset($_GET['export']) && $_GET['export'] == 1)){
			$array = array();
			$adSpacArray = $this->getSpaceArray();
			if($_GET['indexType'] == "all" || $_GET['indexType'] == "mf"){
				$mob = TosTreporBuyDrDisplayHourlyMobReport::model()->getHourlyByDay();
				foreach($mob as $value){
					$array[$value->settled_time]["settled_time"] = $value->settled_time;
					$array[$value->settled_time]["adSpace"] = $adSpace;
					$array[$value->settled_time]["media_cost"] += $value->media_cost;
					$array[$value->settled_time]["click"] += $value->click;
					$array[$value->settled_time]["impression"] += $value->impression;
					$array[$value->settled_time]["pv"] += $value->pv;
				}				
			}
			
			if($_GET['indexType'] == "all" || $_GET['indexType'] == "cf"){
				$pc = TosTreporBuyDrDisplayHourlyPcReport::model()->getHourlyByDay();
				foreach($pc as $value){
					$array[$value->settled_time]["settled_time"] = $value->settled_time;
					$array[$value->settled_time]["media_cost"] += $value->media_cost;
					$array[$value->settled_time]["click"] += $value->click;
					$array[$value->settled_time]["impression"] += $value->impression;
					$array[$value->settled_time]["pv"] += $value->pv;
				}				
			}
					
			ksort($array);
			$hourlyData = array();
			foreach ($array as $key => $value) {
				$hourlyData[] = $value;
			}

			if(isset($_GET['export']) && $_GET['export'] == 1){
				foreach ($hourlyData as $value) {

					$data[] = array(
						"A" => $value["settled_time"],
						"B" => number_format($value["impression"], 0, "." ,""),
						"C" => number_format($value["click"], 0, "." ,""),
						"D" => (($value["impression"] > 0) ? round(($value["click"] / $value["impression"]) * 100, 2) : 0) . "%",
						"E" => number_format($value["media_cost"], 2, "." ,""),
						"F" => (($value["impression"] > 0) ? number_format(($value["media_cost"] / $value["impression"]) * 1000, 2, "." ,"") : 0),
						"G" => (($value["click"] > 0) ? number_format(($value["media_cost"] / $value["click"]), 2, "." ,"") : 0),
					);
					$impression += $value["impression"];
					$click += $value["click"];
					$media_cost += $value["media_cost"];

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

				$data[] = array();
				$day = (empty($_GET["day"])? date("Y-m-d") : $_GET["day"]);
				$data[] = array( "A" => "查詢日期 : " . $day);

				if( (isset($_GET['supplierId']) && $_GET['supplierId'] > 0) || (isset($_GET['siteId']) && $_GET['siteId'] > 0)  || (isset($_GET['adSpaceId']) && $_GET['adSpaceId'] > 0)){ 
					$data[] = array( "A" => "供應商 : " . $supplier->name); 
				}

				if( (isset($_GET['siteId']) && $_GET['siteId'] > 0)  || (isset($_GET['adSpaceId']) && $_GET['adSpaceId'] > 0)){ 
					$data[] = array( "A" => "網站 : " . $supplier->site[0]->name); 
				}

				if(isset($_GET['adSpaceId']) && $_GET['adSpaceId'] > 0){ 
					$data[] = array( "A" => "版位 : " . $supplier->site[0]->adSpace[0]->name); 
				}

				$report = array(
					"name" => "供應商時報表",
					"titleName" => "供應商時報表 查詢時間" . $_GET['day'],
					"fileName" => "供應商時報表 查詢時間" . $_GET['day'],
					"width" => "G1",
					"title" => array(
						"A2" => "時段",
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

			$dataProvider=new CArrayDataProvider($hourlyData, array(
				'pagination'=>false,
			));
			$this->renderPartial('_supplierHourlyReport',array(
				'dataProvider'=>$dataProvider,
				'supplier' => $adSpacArray['supplier'],
			));	
			Yii::app()->end();
		}

		$this->render('supplierHourlyReport',array(
			"dataProvider" => $dataProvider
		));
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