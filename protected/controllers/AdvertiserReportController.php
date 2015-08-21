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
		$criteria->addCondition("tos_id = '" . $campaignId . "'");
		return $model = Campaign::model()->find($criteria);
		
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
			$model = EveTestYtbLogs::model()->ytbReport($_GET['CampaignId']);
	
			$data = array();

			$impression = 0;
			$click = 0;
			$income = 0;				
			foreach ($model as $value) {
				$data[] = array(
					"A" => $value["date"],
					"B" => "(" . $value["strategyId"] . ")" . $value["strategy"],
					"C" => "(" . $value["creativeId"] . ")" . $value["creative"],
					"D" => "(" . $value["adspaceId"] . ")" . $value["adspace"],
					"E" => $value["siteCategory"],
					"F" => $value["totView"],
					"G" => $value["25"],
					"H" => $value["50"],
					"I" => $value["75"],
					"J" => $value["100"],

				);
				$totView += $value["totView"];
				$tot25 += $value["25"];
				$tot50 += $value["50"];
				$tot75 += $value["75"];
				$tot100 += $value["100"];
			}

			$data[] = array(
				"A" => "",
				"B" => "",
				"C" => "",
				"D" => "",
				"E" => "合計",
				"F" => $totView,
				"G" => $tot25,
				"H" => $tot50,
				"I" => $tot75,
				"J" => $tot100,
			);

			$report = array(
				"name" => "影音活動報表",
				"titleName" => "(" . $campaign->tos_id . ")" . $campaign->campaign_name . " 影音活動報表 查詢時間" . $day[0] . "~" . $day[1],
				"fileName" => "影音活動報表 查詢時間" . $day[0] . "~" . $day[1],
				"width" => "J1",
				"title" => array(
					"A2" => "日期",
					"B2" => "策略",
					"C2" => "素材",
					"D2" => "版位",
					"E2" => "類別",
					"F2" => "收視數",
					"G2" => "25%收視數",
					"H2" => "50%收視數", 
					"I2" => "75%收視數", 
					"J2" => "100%收視數", 
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
			$model = new EveTestYtbLogs('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['EveTestYtbLogs']))
				$model->attributes=$_GET['EveTestYtbLogs'];

			$this->renderPartial('_ytbReport',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('ytbReport');
	}

}