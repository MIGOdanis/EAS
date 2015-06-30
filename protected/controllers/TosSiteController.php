<?php

class TosSiteController extends Controller
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

	
	public function actionAdmin()
	{	
		$criteria = new CDbCriteria;
		$criteria->addCondition("name = 'lastSyncSite'");
		$lastSync = Log::model()->find($criteria);	

		$model = new Site('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Site']))
			$model->attributes=$_GET['Site'];

		$this->render('admin',array(
			'model'=>$model,
			'lastSync'=>$lastSync
		));
	}

	public function actionView($id)
	{				
		$model = $this->loadModel($id);

		$this->renderPartial('view',array(
			"model" => $model
		));
	}

	public function loadModel($id)
	{
		$model=Site::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}