<?php

class AdvertiserAccountsController extends Controller
{
	public function actionAdmin()
	{
	if(isset($_GET['export']) && $_GET['export'] == 1){
			$day = $this->getDay();
			//$campaign = $this->getCampaign($_GET['CampaignId']);
			$model = BuyReportDailyPc::model()->advertiserAccountsReport($_GET['CampaignId']);
	
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
			//$campaign = $this->getCampaign($_GET['CampaignId']);
			//print_r($_GET['CampaignId']); exit;
			$model = new BuyReportDailyPc('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['BuyReportDailyPc']))
				$model->attributes=$_GET['BuyReportDailyPc'];

			$this->renderPartial('_report',array(
				'model'=>$model,
				'campaign'=>$campaign,
				'day'=>$day

			));	
			Yii::app()->end();
		}

		$this->render('admin');
	}
}