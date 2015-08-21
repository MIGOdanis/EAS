<?php

class RegisterSupplierController extends Controller
{
	public function actionIndex()
	{
		$this->layout='column_register_supplier';
		if(isset($_GET['id']) && isset($_GET['k'])){
			$criteria = new CDbCriteria; 
			$criteria->addCondition("t.id = " . (int)$_GET['id']);
			$criteria->addCondition("t.public_time = " . $_GET['k']);
			$criteria->addCondition("t.check = 2 OR t.check = 5 OR t.check = 7");
			$model = SupplierRegister::model()->find($criteria);

			if($model === null)
				throw new CHttpException(403,'權限不足');

			$model->scenario = 'register';
		}else{
			$model = new SupplierRegister('register');//
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SupplierRegister']))
		{
			//print_r($_FILES); exit;
			$time = time();
			$model->attributes=$_POST['SupplierRegister'];
			$model->name = $model->company_name;
			$model->public_time = 0;
			$model->create_time = time();
			$model->check_time = 0;
			$model->check = ($model->check == 5 || $model->check == 7) ? 6 : 1;
			$model->check_by = 0;

			if (isset($_FILES['bank_book_img']) && !empty($_FILES['bank_book_img']['name'])) {
				$folder = Yii::app()->params['uploadFolder'] . "registerSupplier/" . date("Ymd");
				$uploadMsg  = $this->upload_image_resize(
					$_FILES['bank_book_img'], 
					false,
					$folder,
					md5($_FILES['bank_book_img']['name'].$time)
			  	);
				if($uploadMsg === true){
					$model->bank_book_img = date("Ymd") . "/" . md5($_FILES['bank_book_img']['name'].$time);
				}else{
					// print_r($uploadMsg); exit;
				}
			}

			if (isset($_FILES['certificate_image']) && !empty($_FILES['certificate_image']['name'])) {
				$folder = Yii::app()->params['uploadFolder'] . "registerSupplier/" . date("Ymd");
				$uploadMsg  = $this->upload_image_resize(
					$_FILES['certificate_image'], 
					false,
					$folder,
					md5($_FILES['certificate_image']['name'].$time)
			  	);
				if($uploadMsg === true){
					$model->certificate_image = date("Ymd") . "/" . md5($_FILES['certificate_image']['name'].$time);
				}else{
					//print_r($uploadMsg); exit;
				}
			}			

			if($model->type == 1 || $model->type == 2){
				$model->scenario = 'register';
				$model->invoice_name = $model->company_name;
			}else{
				$model->scenario = 'companyRegister';
			}

			if($_POST['SupplierRegister']['read_contract'] == 1 || $model->read_contract == 1){
				$model->read_contract = 1;
			}else{
				$model->read_contract = null;
			}

			if($model->save()){
				$this->redirect(array('afterRegister'));
			}
		}

		$this->render('index',array(
			'model'=>$model,
		));
	}

	public function actionAfterRegister()
	{
		$this->layout='column_register_supplier';
		$this->render('afterRegister');
	}

}