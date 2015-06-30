<?php
class SupplierNaviWidget extends CWidget
{
	public $controller;

	public function init()
	{

	}
	
	public function run()
	{   
		$this->render('supplierNaviWidget', array("controller" => $this->controller));
	}
}
