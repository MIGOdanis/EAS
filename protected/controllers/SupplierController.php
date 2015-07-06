<?php

class SupplierController extends Controller
{

	public $layout = "supplier_column1";
	public $site;
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
		return $this->checkSupplierAuth();
	}
	//權限驗證模組


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

		if(isset($_GET['type']) && $_GET['type'] == "downloadIV"){
			$this->CreatIV();
			Yii::app()->end();
		}

		$accountsStatus = SiteSetting::model()->getValByKey("accounts_status");

		$this->layout = "supplier_column2";
		$model = SupplierApplicationMonies::model()->getSupplierMonies($this->supplier->tos_id);

		
		if(isset($_GET['type']) && $_GET['type'] == "applicationPay" && $model->application_type != 1){
			if($this->applicationPay()){
				$this->redirect(array('payments'));
			}
		}
		
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

	public function actionMySite()
	{

		if(isset($_GET['type']) && $_GET['type'] == "downloadIV"){
			$this->CreatIV();
			Yii::app()->end();
		}

		$this->render('payments',array(
			'model'=>$model,
			'lastApplication'=>$lastApplication,
			'accountsStatus'=>$accountsStatus,
			'thisApplication' => $thisApplication,
		));
	}

	public function actionReport()
	{
		$this->layout = "supplier_column_report";

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.supplier_id = " . $this->supplier->tos_id);
		$criteria->addCondition("t.status = 1");
		$criteria->addCondition("adSpace.status = 1");
		$this->site = Site::model()->with("adSpace")->findAll($criteria);

		$this->render('report',array(
			'model'=>$model,
		));
	}

	public function actionGetSupplierReport()
	{
		$model = new BuyReportDailyPc('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BuyReportDailyPc']))
			$model->attributes=$_GET['BuyReportDailyPc'];

		$this->renderPartial('_supplierReport',array(
			'model'=>$model,
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

	public function actionRepassword()
	{
		$model=User::model()->findByPk(Yii::app()->user->id);
		$model->scenario = 'repassword';

		if(isset($_POST['User']))
		{
			if (crypt($_POST['User']['password'],$model->password)===$model->password){
				$model->attributes=$_POST['User'];
				$model->password = $model->hashPassword($_POST['User']['new_password']);
				if ($model->save()) {
					$this->email($model->user, "CLICKFORCE 密碼修改通知", "您的密碼已於".date("Y-m-d H:i:s") . "修改完成");
					$this->redirect(array('login/out'));
				}			
			}else{
				$passCheck = true;
			}
		}
		$model->password = "";
		$this->render('repassword',array(
			'model'=>$model,
			'passCheck'=>$passCheck
		));
	}

	public function creatIV(){
		set_time_limit(0);
		ini_set('memory_limit', '1024M');		
		require_once dirname(__FILE__).'/../extensions/PHPWord_CloneRow/PHPWord.php';
		require_once dirname(__FILE__).'/../extensions/PHPWord_CloneRow/PHPWord/Autoloader.php';
		require_once dirname(__FILE__).'/../extensions/PHPWord_CloneRow/PHPWord/DocumentProperties.php';
		require_once dirname(__FILE__).'/../extensions/PHPWord_CloneRow/PHPWord/Template.php';

		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");
		$SupplierApplicationMonies = SupplierApplicationMonies::model()->getSupplierMonies($this->supplier->tos_id);

		$PHPWord = new PHPWord();
		$document = $PHPWord->loadTemplate(dirname(__FILE__).'/../extensions/wordTemp/iv.docx');

		$titleName = ($this->supplier->type == 1 || $this->supplier->type == 2)? "姓名" : "公司名稱";
		$taxid = ($this->supplier->type == 1 || $this->supplier->type == 2)? "身分證字號" : "統一編號";
		
		$document->setValue('title_name', $titleName);
		$document->setValue('name',  $this->supplier->invoice_name . "(" . $this->supplier->tos_id  . ")");
		$document->setValue('y',  (date("Y",$monthOfAccount->value) - 1911));
		$document->setValue('m',  date("m",$monthOfAccount->value));
		$document->setValue('pay_y',  (date("Y") - 1911));
		$document->setValue('pay_m',  date("m"));
		$document->setValue('pay_d',  date("t"));
		$document->setValue('title_taxid',  $taxid);
		$document->setValue('taxid',  $this->supplier->tax_id);
		$document->setValue('address',  $this->supplier->company_address);
		$document->setValue('mail_address',  $this->supplier->mail_address);
		$document->setValue('type', Yii::app()->params['supplierType'][$this->supplier->type]);
		$document->setValue('totle_pay', number_format($this->tax($this,$SupplierApplicationMonies->count_monies), 0, "." ,","));
		$document->setValue('tax_pay', number_format($this->taxDeduct($this,$SupplierApplicationMonies->count_monies), 0, "." ,","));
		$document->setValue('pay', number_format($this->taxDeductTot($this,$SupplierApplicationMonies->count_monies), 0, "." ,","));

		$tax = Yii::app()->params['taxTypeDeduct'][$this->supplier->type];
		if($this->supplier->type == 1 && $count_monies < 20000)
			$tax = 1;
		$document->setValue('tax', (1 - $tax) * 100);

		$temp_file = tempnam(dirname(__FILE__), 'PHPWord');
		$document->save($temp_file);
		header("Content-Disposition: attachment; filename='支領證明單_" . date("Y-m") . ".docx'");
		readfile($temp_file); 
		unlink($temp_file); 

		exit;
	}

	public function tax($value,$count_monies){
		$tax = Yii::app()->params['taxType'][$value->supplier->type];
		if($value->supplier->type == 1 && $count_monies < 20000)
			$tax = 1;

		return $count_monies * $tax;
		
	}

	public function taxDeductTot($value,$count_monies){
		$tax =  $this->tax($value,$count_monies);
		$taxDeduct = Yii::app()->params['taxTypeDeduct'][$value->supplier->type];
		if($value->supplier->type == 1 && $count_monies >= 20000)
			$taxDeduct = 0.9;

		return $tax * $taxDeduct;
		
	}

	public function taxDeduct($value,$count_monies){
		$tax =  $this->tax($value,$count_monies);
		$taxDeduct = $this->taxDeductTot($value,$count_monies);

		return $tax - $taxDeduct;
		
	}
	
}