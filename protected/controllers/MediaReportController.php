<?php

class MediaReportController extends Controller
{
	public function actionCategoryReport()
	{
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

}