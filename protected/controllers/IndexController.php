<?php
class IndexController extends Controller
{
	public function actionIndex()
	{
		if(!Yii::app()->user->id)
			$this->redirect(array("login/index"));

		if($this->user->group == 7)
			$this->redirect(array("supplier/index"));

		$this->layout = "column1";

		$model=new Message('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Message']))
			$model->attributes=$_GET['Message'];

		$this->render('index',array(
			'model'=>$model,
		));		
	}

	public function actionMessageView($id)
	{	
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.user_id LIKE '%:" . Yii::app()->user->id . ":%'");
		$criteria->addCondition("t.active = 1");
		$criteria->addCondition("t.publish_time <= " . time());
		$criteria->addCondition("t.expire_time >= " . time() . " OR t.expire_time = 0");
		$criteria->addCondition("t.id = :id");
		$criteria->params = array(
			':id' => $id
		);
		$model = Message::model()->find($criteria);

		if($model !== null){
			$criteria = new CDbCriteria;
			$criteria->addCondition("t.message_id = :mid");
			$criteria->addCondition("t.user_id = :uid");
			$criteria->params = array(
				':uid' => Yii::app()->user->id,
				':mid' => $id,
			);
			$read = MessageRead::model()->find($criteria);
			if($read === null){
				$read = new MessageRead();
				$read->message_id = $model->id;
				$read->user_id = Yii::app()->user->id;
				$read->read_time = time();
				$read->save();
			}
		}
		

		$this->renderPartial('_messageView',array(
			'model'=>$model,
		));
	}	

}