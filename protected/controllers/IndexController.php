<?php
class IndexController extends Controller
{
	public function actionIndex()
	{
		if(!Yii::app()->user->id)
			$this->redirect(array("login/index"));

		if($this->user->group == 7)
			$this->redirect(array("supplier/index"));

		$this->render('index');
	}

}