<?php

class SupplierYearAccountsController extends Controller
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
		if (isset($_GET['export'])) {
			$this->exportSupplierYearAccounts();
			Yii::app()->end();
		}
		$model = new SupplierYearAccounts('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierYearAccounts']))
			$model->attributes=$_GET['SupplierYearAccounts'];

		$this->render('admin',array(
			'model'=>$model
		));
	}

	public function exportSupplierYearAccounts()
	{	
		$model = SupplierYearAccounts::model()->search();

		// print_r($model); exit;

		$data = array();	
		$application_type = array("未請款","請款中","已請款");			
		foreach ($model as $value) {
			// print_r($value->supplier->name);
			$data[] = array(
				"A" => $value->supplier_id,
				"B" => $value->supplier->name,
				"C" => $value->site_id,
				"D" => $value->site->name,
				"E" => $value->adSpace_id,
				"F" => $value->adSpace->name,
				"G" => $value->total_monies,
				"H" => date("Y-m",$value->last_application),
				"I" => date("Y-m",$value->this_application),
				"J" => $value->year,
				"K" => $application_type[$value->application_type]
			);
		}

		$report = array(
			"name" => "年度供應商凍結款項報表",
			"titleName" => $_GET['year'] . "年度供應商凍結款項報表",
			"fileName" => $_GET['year'] . "年度供應商凍結款項報表",
			"width" => "K1",
			"title" => array(
				"A2" => "供應商",
				"B2" => "供應商編號",
				"C2" => "網站",
				"D2" => "網站編號",
				"E2" => "版位",
				"F2" => "版位編號",				
				"G2" => "總額",
				"H2" => "款項月份(起)",
				"I2" => "款項月份(迄)",
				"J2" => "年度",
				"K2" => "請款狀態"
			),
			"data" => $data
		);	

		// print_r($report);
		// exit;

		$this->exportExcel($report);
		Yii::app()->end();	
	}	
}