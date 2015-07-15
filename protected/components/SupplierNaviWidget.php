<?php
class SupplierNaviWidget extends CWidget
{
	public $controller;
	public $supplier;
	public $user;

	public function init()
	{

	}
	
	public function run()
	{   
		$this->render('supplierNaviWidget', array("controller" => $this->controller, "user" => $this->user, "supplier" => $this->supplier));
	}
}
