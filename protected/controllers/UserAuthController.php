<?php

class UserAuthController extends Controller
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
		$model = new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model = User::model()->with("auth")->findByPk($id);

		if(isset($_POST['auth_id']))
		{
			$model->auth_id = $_POST['auth_id'];
			header('Content-type: application/json');
			if ($model->save()) {
				echo json_encode(array("code" => "1"));
			}else{
				echo json_encode(array("code" => "0"));
			}

			Yii::app()->end();
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("group_id = " . $model->group);
		$criteria->addCondition("active = 1");
		$authGroup = AuthGroup::model()->findAll($criteria);
		$this->renderPartial('_update',array(
			"authGroup" => $authGroup,
			"model" => $model
		));
	}

}