<?php

class MessageController extends Controller
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
		$model=new Message('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Message']))
			$model->attributes=$_GET['Message'];


		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$model=new Message();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Message']))
		{
			$model->attributes=$_POST['Message'];

			if(!empty($_POST['Message']['publish_time'])){
				$model->publish_time = strtotime($_POST['Message']['publish_time'] . " " . $_POST['publish_time_hour']);
			}else{
				$model->publish_time = "";
			}

			if(isset($_POST['noExpireTime']) && $_POST['noExpireTime'] == 1){
				$model->expire_time = 0;
			}else{
				if(!empty($_POST['Message']['expire_time'])){
					$model->expire_time = strtotime($_POST['Message']['expire_time'] . " " . $_POST['expire_time_hour']);
				}else{
					$model->expire_time = "";
				}
			}
			
			$model->cron_mail = (isset($_POST['Message']['cron_mail']) && $_POST['Message']['cron_mail'] == 1) ? 1 : 0;

			$model->create_by = Yii::app()->user->id;
			$model->create_time = time();

			if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
				$model->user_id = ":" . implode (":", $_POST['user_id']) . ":";
			}
			
			$model->active = 0;
			$model->send_mail = 0;

			// print_r($model); exit; 
			if($model->save()){
				$this->redirect(array('admin'));
			}
		}

		if($model->user_group > 0){

			$criteria = new CDbCriteria; 
			$criteria->addCondition("`group` = :id");
			$criteria->params = array(
				':id' => $model->user_group
			);
			$user = User::model()->findAll($criteria);

		}		

		$this->render('create',array(
			'model'=>$model,
			'user'=>$user
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		

		if(isset($_POST['Message']))
		{
			$model->attributes = $_POST['Message'];
			if ($model->save()) {
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}	

	public function actionView($id)
	{
		$model=$this->loadModel($id);

		$user_id = explode(":", $model->user_id);

		$uid = array();
		foreach ($user_id as $key => $value) {
			if(!empty($value)){
				$uid[] = $value;
			}
		}

		$criteria = new CDbCriteria; 
		$criteria->addInCondition("t.id", $uid);

		// print_r($criteria); exit;

		if($_POST['id'] == 7){
			$criteria->with = "supplier";
		}

		$user = User::model()->findAll($criteria);

		$this->render('view',array(
			'model'=>$model,
			"user" => $user,
		));
	}	


	public function actionGetGroupUser()
	{				
		if(isset($_POST['id'])){
			$user_id = array();
			$criteria = new CDbCriteria; 
			$criteria->addCondition("`group` = :id");

			if($_POST['id'] == 7){
				$criteria->with = "supplier";
			}

			$criteria->params = array(
				':id' => $_POST['id']
			);
			$user = User::model()->findAll($criteria);

			$this->renderPartial('_getGroupUser',array(
				"user" => $user,
				"user_id" => $user_id,
			));
		}
	}

	public function loadModel($id)
	{
		$model=Message::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}