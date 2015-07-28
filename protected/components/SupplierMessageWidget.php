<?php
class SupplierMessageWidget extends CWidget
{
	// public $controller;
	public $userId;
	public $_model;
	public $_unRead;
	public function init()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.user_id LIKE '%:" . Yii::app()->user->id . ":%'");
		$criteria->addCondition("t.active = 1");
		$criteria->addCondition("t.publish_time <= " . time());
		$criteria->addCondition("t.expire_time >= " . time() . " OR t.expire_time = 0");
		$criteria->limit = 6;
		$criteria->order = 't.publish_time DESC';
		$this->_model = Message::model()->findAll($criteria);
		$this->_unRead = Message::model()->getUnReadCount();
	}
	
	public function run()
	{   
		$this->render('supplierMessageWidget', array("model" => $this->_model, "unRead" => $this->_unRead));
	}
}
