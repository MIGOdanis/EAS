<?php

class TosSupplierController extends Controller
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
		$criteria = new CDbCriteria;
		$criteria->addCondition("name = 'lastSyncSupplier'");
		$lastSync = Log::model()->find($criteria);	

		$model = new Supplier('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Supplier']))
			$model->attributes=$_GET['Supplier'];

		$this->render('admin',array(
			'model'=>$model,
			'lastSync'=>$lastSync
		));
	}

	public function actionView($id)
	{				
		$model = $this->loadModel($id);

		$this->renderPartial('view',array(
			"model" => $model
		));
	}

	public function actionUpdate($id)
	{				
		$model = $this->loadModel($id);
		if(isset($_POST['Supplier']))
		{		
			$model->attributes=$_POST['Supplier'];
			if($model->save()){
				$savelog = new SupplierUpdated();
				$savelog->o_id = $model->id;
				$savelog->tos_id = $model->tos_id;
				$savelog->name = $model->name;
				$savelog->contacts = $model->contacts;
				$savelog->contacts_email = $model->contacts_email;
				$savelog->contacts_tel = $model->contacts_tel;
				$savelog->contacts_moblie = $model->contacts_moblie;
				$savelog->contacts_fax = $model->contacts_fax;
				$savelog->tel = $model->tel;
				$savelog->fax = $model->fax;
				$savelog->email = $model->email;
				$savelog->mobile = $model->mobile;
				$savelog->company_name = $model->company_name;
				$savelog->company_address = $model->company_address;
				$savelog->mail_address = $model->mail_address;
				$savelog->invoice_name = $model->invoice_name;
				$savelog->tax_id = $model->tax_id;
				$savelog->type = $model->type;
				$savelog->country_code = $model->country_code;
				$savelog->account_name = $model->account_name;
				$savelog->account_number = $model->account_number;
				$savelog->bank_name = $model->bank_name;
				$savelog->bank_id = $model->bank_id;
				$savelog->bank_sub_name = $model->bank_sub_name;
				$savelog->bank_sub_id = $model->bank_sub_id;
				$savelog->bank_type = $model->bank_type;
				$savelog->bank_swift = $model->bank_swift;
				$savelog->remark = $model->remark;
				$savelog->create_time = $model->create_time;
				$savelog->update_by = Yii::app()->user->id;
				$savelog->update_time = time();
				$savelog->certificate_image = $model->certificate_image;
				$savelog->bank_book_img = $model->bank_book_img;
				$savelog->save();

				$this->redirect(array('admin'));
			}
		}
		$this->render('update',array(
			"model" => $model
		));
	}

	public function actionUpdateLog()
	{	
		$model = new SupplierUpdated();
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierUpdated']))
			$model->attributes=$_GET['SupplierUpdated'];

		$this->render('updateLog',array(
			'model'=>$model,
		));
	}

	public function actionUpdateLogView($id)
	{				
		$model = SupplierUpdated::model()->findByPk($id);

		$this->renderPartial('updateLogView',array(
			"model" => $model
		));
	}

	public function actionSupplierUserList($id)
	{				
		$supplier = $this->loadModel($id);

		$model = new User();
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('supplierUserList',array(
			'model'=>$model,
			'supplier'=>$supplier
		));
	}

	public function actionSupplierUserCreate($id)
	{			
		$model = $this->loadModel($id);
		if(isset($_POST['name']) && $_POST['mail']){
			$passwd = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
			$user=new User('register');
			$user->user = $_POST['mail'];
			$user->password = $passwd;
			$user->repeat_password = $passwd;
			$user->name = $_POST['name'];
			$user->group = 7;
			$user->auth_id = 0;
			$user->active = 1;
			$user->supplier_id = $model->id;
			$user->creat_time = time();		
			$user->password = $user->hashPassword($passwd);
			if($user->save()){
				$this->email($_POST['mail'], "供應商平台帳號申請通知", "您申請的帳號[" . $_POST['name'] . "]已經通過審核! <br> 您可以使用帳號" . $_POST['mail'] . "密碼<br> " . $passwd ."<br> 登入您的後台");
				echo json_encode(array("code" => "1"));
				Yii::app()->end();
			}else{
				echo json_encode(array("code" => "0"));
				Yii::app()->end();
			}
		}else{
			$this->renderPartial('_userForm',array(
				"model" => $model
			));
		}
	}

	public function loadModel($id)
	{
		$model=Supplier::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}