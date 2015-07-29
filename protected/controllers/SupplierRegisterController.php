<?php

class SupplierRegisterController extends Controller
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
		$model = new SupplierRegister('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierRegister']))
			$model->attributes=$_GET['SupplierRegister'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionView($id)
	{				
		$model = $this->loadModel($id);

		$this->renderPartial('view',array(
			"model" => $model
		));
	}

	public function actionCheck($id)
	{				
		$model = $this->loadModel($id);

		if($_GET['type'] == 2){
			if(isset($_POST['tosId'])){
				header('Content-type: application/json');
				$model->tos_id = $_POST['tosId'];
				$model->check_time = time();
				$model->check = ($model->check == 6) ? 8 : 3; //通過
				$model->check_by = Yii::app()->user->id;
				if($model->save()){
					$criteria = new CDbCriteria;
					$criteria->addCondition("tos_id = '" . $_POST['tosId'] . "'");
					$checkSupplier = Supplier::model()->find($criteria);
					if($checkSupplier === null && $model->check = 3){
						$supplier = $this->creatSupplier($model);
						if($supplier->id){
							$criteria = new CDbCriteria;
							$criteria->addCondition("user = '" . $model->email . "'", "OR");
							$criteria->addCondition("name = '" . $model->company_name . "'", "OR");
							$checkUser = User::model()->find($criteria);
							if($checkUser !== null){
								//使用者重複								
								echo json_encode(array("code" => "6"));
								Yii::app()->end();
							}							
							if($this->creatNewUser($model,$supplier)){
								//完成								
								echo json_encode(array("code" => "1"));
								Yii::app()->end();
							}else{
								//使用者建立失敗								
								echo json_encode(array("code" => "2"));
								Yii::app()->end();							
							}

						}else{
							//供應商建立失敗							
							echo json_encode(array("code" => "3"));
							Yii::app()->end();
						}
					}else{
						if($model->check = 8){
							$checkSupplier->attributes = $model->attributes;
							if($checkSupplier->save()){
								echo json_encode(array("code" => "7"));
								Yii::app()->end();
							}else{
								echo json_encode(array("code" => "8"));
								Yii::app()->end();									
							}
						}else{
							//tos-id已經存在							
							echo json_encode(array("code" => "4"));
							Yii::app()->end();	
						}					
					}
				}else{
					//更新審核狀態失敗					
					echo json_encode(array("code" => "5"));
					Yii::app()->end();
				}				
				echo json_encode(array("code" => "0"));
				Yii::app()->end();
			}else{
				$this->renderPartial('_checkForm',array(
					"model" => $model
				));
			}
		}elseif($_GET['type'] == 1){
			if($model->public_time == 0)
				$model->public_time = time();
			$model->check_time = time();
			$model->check = ($model->check == 6 || $model->check == 5) ? 7 : 2; //退回
			$model->check_by = Yii::app()->user->id;
			$model->save();
			$this->renderPartial('_turnBack',array(
				"model" => $model
			));
		}
	}

	public function creatSupplier($model)
	{
		$supplier = new Supplier();
		$supplier->tos_id = $model->tos_id;
		$supplier->name = $model->name;
		$supplier->contacts = $model->contacts;
		$supplier->contacts_email = $model->contacts_email;
		$supplier->contacts_tel = $model->contacts_tel;
		$supplier->contacts_moblie = $model->contacts_moblie;
		$supplier->contacts_fax = $model->contacts_fax;
		$supplier->fax = $model->fax;
		$supplier->tel = $model->tel;
		$supplier->email = $model->email;
		$supplier->mobile = $model->mobile;
		$supplier->company_name = $model->company_name;
		$supplier->company_address = $model->company_address;
		$supplier->mail_address = $model->mail_address;
		$supplier->invoice_name = $model->invoice_name;
		$supplier->tax_id = $model->tax_id;
		$supplier->type = $model->type;
		$supplier->country_code = $model->country_code;
		$supplier->account_name = $model->account_name;
		$supplier->account_number = $model->account_number;
		$supplier->bank_name = $model->bank_name;
		$supplier->bank_id = $model->bank_id;
		$supplier->bank_sub_name = $model->bank_sub_name;
		$supplier->bank_sub_id = $model->bank_sub_id;
		$supplier->bank_type = $model->bank_type;
		$supplier->bank_swift = $model->bank_swift;
		$supplier->bank_swift2 = $model->bank_swift2;
		$supplier->create_time = time();
		$supplier->sync_time = time();
		$supplier->status = 1;
		$supplier->certificate_image = $model->certificate_image;
		$supplier->bank_book_img = $model->bank_book_img;		
		$supplier->save();
		return $supplier;
	}

	public function creatNewUser($model,$supplier)
	{
		$passwd = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$user=new User('register');
		$user->user = $model->email;
		$user->password = $passwd;
		$user->repeat_password = $passwd;
		$user->name = $model->contacts;
		$user->group = 7;
		$user->auth_id = 0;
		$user->active = 1;
		$user->supplier_id = $supplier->id;
		$user->creat_time = time();		
		$user->password = $user->hashPassword($passwd);
		if($user->save()){
			$Body = file_get_contents(dirname(__FILE__).'/../extensions/wordTemp/mailTemp.html');
			$Body = str_replace ("{body}","您申請的" . $model->company_name . "供應商電子合約已完成審! <br> 您可以使用<br>帳號:" . $model->email . "<br>密碼: " . $passwd ."<br> <a href='" . Yii::app()->params['baseUrl'] . "'>登入您的後台</a>",$Body);			
			$this->email($model->email, "CLICKFORCE 電子合約申請通知", $Body);
			return true;
		}else{
			// print_r($user->getErrors()); exit;
			return false;
		}
	}



	public function loadModel($id)
	{
		$model=SupplierRegister::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

}