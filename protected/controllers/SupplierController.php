<?php

class SupplierController extends Controller
{

	public $layout = "supplier_column1";

	// //權限驗證模組
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 		'postOnly + delete', // we only allow deletion via POST request
	// 	);
	// }

	// public function accessRules()
	// {
	// 	return $this->checkSupplierAuth();
	// }
	// //權限驗證模組


	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionGetIndexReport()
	{
		$model = new BuyReportDailyPc('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BuyReportDailyPc']))
			$model->attributes=$_GET['BuyReportDailyPc'];

		$this->renderPartial('_indexReport',array(
			'model'=>$model,
		));
	}

	public function actionPayments()
	{

		$accountsStatus = SiteSetting::model()->getValByKey("accounts_status");
		if(isset($_GET['type']) && $_GET['type'] == "applicationPay" && $accountsStatus == 1){
			if($this->applicationPay()){
				$this->redirect(array('payments'));
			}
		}
		$this->layout = "supplier_column2";
		$model = SupplierApplicationMonies::model()->getSupplierMonies($this->supplier->tos_id);

		$criteria=new CDbCriteria;
		$criteria->addCondition("supplier_id = " . $this->supplier->tos_id);
		$criteria->addCondition("status = 3");
		$criteria->order = "id DESC";
		$lastApplication = SupplierApplicationLog::model()->find($criteria);


		$criteria=new CDbCriteria;
		$criteria->addCondition("supplier_id = " . $this->supplier->tos_id);
		$criteria->addCondition("status > 0");
		$criteria->addCondition("status > 0");
		$criteria->order = "id DESC";
		$thisApplication = SupplierApplicationLog::model()->find($criteria);

		$this->render('payments',array(
			'model'=>$model,
			'lastApplication'=>$lastApplication,
			'accountsStatus'=>$accountsStatus,
			'thisApplication' => $thisApplication,
		));
	}

	function applicationPay(){
		//echo $this->supplier->tos_id; exit;
		$criteria = new CDbCriteria;
		$criteria->select = '(sum(t.total_monies) + sum(t.month_monies)) as count_monies, sum(t.total_monies) as total_monies, sum(t.month_monies) as month_monies, t.id as id, t.supplier_id as supplier_id, t.site_id as site_id, t.this_application as this_application, t.adSpace_id as adSpace_id, t.total_monies as total_monies, t.month_monies as month_monies, t.last_application as last_application, t.application_type as application_type, t.application_id as application_id, t.application_by as application_by, t.create_time as create_time, t.update_time as update_time';
		$criteria->addCondition("supplier_id = " . $this->supplier->tos_id);
		$criteria->group = "supplier_id";	
		$model = SupplierApplicationMonies::model()->find($criteria);
		if($model !== null){
			$criteria = new CDbCriteria;
			$criteria->addCondition("supplier_id = " . $this->supplier->tos_id);
			$criteria->addCondition("year = " . date("Y", $model->this_application));	
			$criteria->addCondition("month = " . date("m", $model->this_application));		
			$criteria->order = "status DESC";
			$log = SupplierApplicationLog::model()->find($criteria);

			if($log === null || $log->status == 0){
				$application = new SupplierApplicationLog();
				$application->status = 1;
				$application->start_time = $model->last_application;
				$application->end_time = $model->this_application;		
				$application->certificate_status = 0;
				$application->certificate_time = 0;
				$application->certificate_by = 0;
				$application->invoice = 0;
				$application->invoice_time = 0;
				$application->invoice_by = 0;
				$application->monies = $model->count_monies;
				$application->year = date("Y", $model->this_application);
				$application->month = date("m", $model->this_application);
				$application->application_time = time();
				$application->application_by = Yii::app()->user->id;
				$application->supplier_id = $this->supplier->tos_id;
				$application->lock = 1;
				$application->pay_time = 0;
				if($application->save()){
					SupplierApplicationMonies::model()->updateAll(
						array(
							'application_type' => 1,
							'application_id' => $application->id,
							'application_by' => Yii::app()->user->id
						),
						'supplier_id = ' . $this->supplier->tos_id
					);
					return true;
				}
			}							
		}	
		return false;	
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}