<?php

class TosAdSpaceApplyController extends Controller
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
		$model = new AdSpaceApply('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AdSpaceApply']))
			$model->attributes=$_GET['AdSpaceApply'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionView($id)
	{				
		$model = $this->loadModel($id);

		$this->renderPartial('view',array(
			"model" => $model
		));
	}

	public function actionStatus($id)
	{				
		$model = $this->loadModel($id);
		$model->status = 2;
		$model->save();
		$this->renderPartial('view',array(
			"model" => $model
		));		
	}

	public function loadModel($id)
	{
		$model=AdSpaceApply::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}	
}