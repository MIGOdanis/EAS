<?php

class RealtimeReportController extends Controller
{
	public function actionIndex()
	{
		$this->layout = "column1";
		if(isset($_GET['ajax'])){
			$time = date("Y-m-d H:i:s", time() - 30);
			$criteria = new CDbCriteria;
			$criteria->select = '
				sum(t.daily_hit_budget) / 100 as daily_hit_budget,
				sum(t.daily_hit_pv) as daily_hit_pv,
				sum(t.daily_hit_click) as daily_hit_click
			';				
			$criteria->addCondition("t.date = '" . date("Y-m-d 00:00:00") ."'");
			$model = TosCoreCampaignDailyHit::model()->find($criteria);
			if($model !== null){
				echo json_encode(array(
					"daily_hit_budget" => $model->daily_hit_budget,
					"daily_hit_pv" => $model->daily_hit_pv,
					"daily_hit_click" => $model->daily_hit_click,
					"time" => $time
				));
			}else{
				echo "false";
			}
			exit;
		}

		$this->render('index');
	}
}