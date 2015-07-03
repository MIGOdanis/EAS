<?php

class AuthGroupController extends Controller
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

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{

		$model=new AuthGroup('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AuthGroup']))
			$model->attributes=$_GET['AuthGroup'];


		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{

		$model=new AuthGroup;
		if(isset($_POST['AuthGroup']))
		{
			//print_r(json_encode($_POST['auth'])); exit;
			$model->attributes=$_POST['AuthGroup'];
			$model->auth = json_encode($_POST['auth']);
			$model->creat_time = time();
			$model->creat_by = Yii::app()->user->id;
			$model->update_time = time();
			$model->update_by = Yii::app()->user->id;
			$model->active = 1;
			if($model->save()){
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=AuthGroup::model()->findByPk($id);

		if(isset($_POST['AuthGroup']))
		{
			$model->auth = json_encode($_POST['auth']);
			$model->update_time = time();
			$model->update_by = Yii::app()->user->id;			
			if($model->save()){
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}	

}