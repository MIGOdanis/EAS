<?php

class LoginController extends Controller
{
	public function actionIndex()
	{

		$this->layout='column_list';

		if(Yii::app()->user->id > 0)
			$this->redirect(Yii::app()->homeUrl);

		$model=new LoginForm;

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
            $_POST['LoginForm']['user'] = trim($_POST['LoginForm']['user']);
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('index',array('model'=>$model));	
	}
		
	/**
	 * 登出並轉回首頁
	 */
	public function actionOut()
	{
		Yii::app()->user->logout(false);
		$this->redirect("index");
	}
}