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

	public function actionResetPassword()
	{
		$this->layout='column_list';
		$err = false;
		if(Yii::app()->user->id > 0)
			$this->redirect(Yii::app()->homeUrl);

		// collect user input data
		if(isset($_POST['mail']))
		{
			$criteria = new CDbCriteria; 
			$criteria->addCondition("user = :mail");		
			$criteria->params = array(':mail'=>$_POST['mail']);	
			$model = User::model()->find($criteria);
			if($model !== null){
				$passwd = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
				$model->password = $model->hashPassword($passwd);
				if($model->save()){
					$Body = file_get_contents(dirname(__FILE__).'/../extensions/wordTemp/mailTemp.html');
					$Body = str_replace ("{body}","您於" . date("Y-m-d H:i:s") . "申請更換密碼<br> 您的新密碼: " . $passwd ."<br> <a href='" . Yii::app()->params['baseUrl'] . "'>請使用您的新密碼登入</a><br> 若您無申請此變更請立即與我們連繫!",$Body);						
					$this->email($model->user, "CLICKFORCE 密碼更換通知", $Body);
					$this->render('afterResetPassword');
					Yii::app()->end();
				}
			}else{
				$err = true;
			}
		}

		// display the login form
		$this->render('resetPassword',array('err'=>$err));	
	}	
}