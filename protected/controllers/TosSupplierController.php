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

	public function actionDownloadContract($id)
	{				
		$model = $this->loadModel($id);
		$this->exportSupplierContract($model);
	}

	public function actionUpdate($id)
	{				
		$model = $this->loadModel($id);
		if(isset($_POST['Supplier']))
		{		
			$model->attributes=$_POST['Supplier'];
			if($model->save()){
				$this->afterSupplierUpdate($model);
				$this->redirect(array('admin'));
			}
		}
		$this->render('update',array(
			"model" => $model
		));
	}

	public function actionUploadContract($id)
	{
		$model = $this->loadModel($id);

		$upload = false;
		$uploadChk = false;
		$uploadMsg = "";
		if(isset($_FILES['pdf']) && !empty($_FILES['pdf']['name'])){
			$upload = true;
			if($_FILES['pdf']['type'] == "application/pdf"){
				if($_FILES['pdf']['size']/1024000 < 20){
					$upload_folder = Yii::app()->params['uploadFolder'] . "SupplierContract/" . $model->tos_id;
					if(!is_dir($upload_folder)){
						if(!mkdir($upload_folder, 0777, true)){
							$uploadChk = false;
							$uploadMsg = "上載錯誤";	
							break;
						}
					}

					$filename = date("Ymd_His") . "_" . $model->tos_id .".pdf";
					
					if(copy($_FILES['pdf']['tmp_name'],$upload_folder."/".$filename)){
						$uploadChk = true;
						$uploadMsg = "上載完成";
						$uploadData = new UploadContract();
						$uploadData->supplier_id = $id;
						$uploadData->file_name = $filename;
						$uploadData->time = time();
						if(!$uploadData->save()){
							$uploadChk = false;
							$uploadMsg = "上載錯誤";
						}else{
							$this->redirect(array('uploadContract?id=' . $id));
						}
					}else{
						$uploadChk = false;
						$uploadMsg = "上載錯誤";						
					}

				}else{
					$uploadChk = false;
					$uploadMsg = "檔案大小超過 20MB";
				}
			}else{
				$uploadChk = false;
				$uploadMsg = "錯誤的格式";
			}
 			
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("supplier_id = " . $id);
		$allData = UploadContract::model()->findAll($criteria);

		$this->render('uploadContract',array(
			"upload" => $upload,
			"uploadChk" => $uploadChk,
			"uploadMsg" => $uploadMsg,
			"allData" => $allData,
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
				$this->email($_POST['mail'], "CLICKFORCE 供應商平台帳號啟用通知","您申請的帳號[" . $_POST['name'] . "]已經通過審核! <br> 您可以使用<br>帳號:" .$_POST['mail'] . "<br>密碼: " . $passwd ."<br> <a href='" . Yii::app()->params['baseUrl'] . "'>登入您的後台</a>");

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

	public function actionGotoDashboard($id)
	{	
		Yii::app()->session['virtual_id'] = $id;
		$this->redirect(array('supplier/index'));
		//Yii::app()->session['virtual_time_out'] = time() + 600;
	}	

}