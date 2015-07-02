<?php
class SupplierNaviWidget extends CWidget
{
	public $controller;
	public $supplier;

	public function init()
	{

	}
	
	public function run()
	{   
		$this->render('supplierNaviWidget', array("controller" => $this->controller,"supplier" => $this->supplier));
	}
}
