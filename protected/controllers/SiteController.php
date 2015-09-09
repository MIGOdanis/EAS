<?php

class SiteController extends Controller
{
	public function actionError()
	{
		$this->layout = "column_list";
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionRealTime($id)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("t.date = '" . date("Y-m-d 00:00:00") ."'");		
		$criteria->addCondition("t.campaign_id = '" . $id ."'");				
		$model = TosCoreCampaignDailyHit::model()->find($criteria);
		if($model === null){
			echo "今日沒有資料<br>";
			echo date("Y-m-d H:i:s");
		}else{
			echo "訂單編號 : " . $model->campaign_id;
			echo "<br>最後執行 : " . $model->last_changed;
			echo "<br>本日花費 : " . $model->daily_hit_budget / 100;
			echo "<br>本日曝光 : " . $model->daily_hit_pv;
			echo "<br>本日點擊 : " . $model->daily_hit_click;
			echo "<br>資料時間 : " . date("Y-m-d H:i:s");
		}
	}	
}