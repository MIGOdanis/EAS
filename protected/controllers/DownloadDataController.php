<?php

class DownloadDataController extends Controller
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
	
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionSiteAdSpaceInfor()
	{
		$model = TosCoreAdSpace::model()->with("site", "site.category", "site.category.mediaCategory")->findAll();
		$data = array();		
		foreach ($model as $value) {
			$data[] = array(
				"A" => $value->site->name,
				"B" => $value->site->category->mediaCategory->name,
				"C" => $value->site->id,
				"D" => $value->site->domain,
				"E" => $value->id,
			);
		}

		$report = array(
			"name" => "網站版位資訊",
			"titleName" => "網站版位資訊 資料時間" . date("Y-m-d H:i:s"),
			"fileName" => "網站版位資訊 資料時間" . date("Y-m-d H:i:s"),
			"width" => "E1",
			"title" => array(
				"A2" => "網站名稱",
				"B2" => "網站分類",
				"C2" => "網站ID",
				"D2" => "網站URL",
				"E2" => "版位ID",
			),
			"data" => $data
		);	

		$this->exportExcel($report);
		Yii::app()->end();	
	}

	public function actionStrategyInfor()
	{
		$model = TosCoreStrategy::model()->with("campaign", "campaign.industry")->findAll();

		$data = array();		
		foreach ($model as $value) {
			$data[] = array(
				"A" => $value->campaign->id,
				"B" => $value->campaign->campaign_name,
				"C" => $value->campaign->industry->name,
				"D" => $value->id,
				"E" => $value->strategy_name,
			);
		}

		$report = array(
			"name" => "訂單策略資訊",
			"titleName" => "訂單策略資訊 資料時間" . date("Y-m-d H:i:s"),
			"fileName" => "訂單策略資訊 資料時間" . date("Y-m-d H:i:s"),
			"width" => "E1",
			"title" => array(
				"A2" => "訂單ID",
				"B2" => "訂單名稱",
				"C2" => "產業名稱",
				"D2" => "策略ID",
				"E2" => "策略名稱",
			),
			"data" => $data
		);	

		$this->exportExcel($report);
		Yii::app()->end();	
	}	

	public function actionCreativeInfor()
	{
		$model = TosCoreCreativeGroups::model()->with("campaign")->findAll();

		$data = array();		
		foreach ($model as $value) {
			$data[] = array(
				"A" => $value->campaign->id,
				"B" => $value->campaign->campaign_name,
				"C" => $value->campaign->industry->name,
				"D" => $value->id,
				"E" => $value->name,
			);
		}

		$report = array(
			"name" => "訂單素材資訊",
			"titleName" => "訂單素材資訊 資料時間" . date("Y-m-d H:i:s"),
			"fileName" => "訂單素材資訊 資料時間" . date("Y-m-d H:i:s"),
			"width" => "E1",
			"title" => array(
				"A2" => "訂單ID",
				"B2" => "訂單名稱",
				"C2" => "產業名稱",
				"D2" => "素材ID",
				"E2" => "素材名稱",
			),
			"data" => $data
		);	

		$this->exportExcel($report);
		Yii::app()->end();	
	}




}