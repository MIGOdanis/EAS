<?php

class SupplierApplicationListController extends Controller
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
		$accountsStatus = SiteSetting::model()->getValByKey("accounts_status");
		
		$model = new SupplierApplicationLog('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierApplicationLog']))
			$model->attributes=$_GET['SupplierApplicationLog'];

		$this->render('admin',array(
			'model'=>$model,
			"accountsStatus" => $accountsStatus
		));
	}

	public function actionCertificate()
	{
		if(isset($_POST['id'])){
			$model = SupplierApplicationLog::model()->findByPk($_POST['id']);
			
			//修改權限驗證
			if($model->certificate_status == 1 && $model->certificate_by != Yii::app()->user->id){
				throw new CHttpException(403,'The requested page does not exist.');
			}
			
			if($model !== null){
				if($model->certificate_by > 0 && $model->certificate_by != Yii::app()->user->id){
						//已經被確認
						$data = array(
							'code' => 3
						);					
				}else{
					$model->certificate_status = (int)$_POST['type'];
					$model->certificate_by = ($_POST['type'] != 0) ? Yii::app()->user->id : 0;
					$model->certificate_time = ($_POST['type'] != 0) ? time() : 0;
					$model->status = ($_POST['type'] != 0) ? 2 : 1;
					if($model->save()){
						//儲存成功
						$data = array(
							'code' => 1
						);	
					}else{
						//儲存失敗
						$data = array(
							'code' => 2
						);						
					}
				}
			}else{
				//ID錯誤
				$data = array(
					'code' => 0
				);				
			}
		}else{
			//ID錯誤
			$data = array(
				'code' => 0
			);
		}

		header('Content-type: application/json');
		echo CJSON::encode($data);
		Yii::app()->end();
	}

	public function actionInvoice()
	{
		if(isset($_POST['id'])){
			$model = SupplierApplicationLog::model()->findByPk($_POST['id']);
			if($model !== null){
				$model->invoice = $_POST['invoiceNum'];
				$model->invoice_time = time();
				$model->invoice_by = Yii::app()->user->id;
				$model->status = 3;
				if($model->save()){
					//儲存成功
					$data = array(
						'code' => 1
					);	
				}else{
					//儲存失敗
					$data = array(
						'code' => 2
					);						
				}
			}else{
				//ID錯誤
				$data = array(
					'code' => 0
				);				
			}
		}else{
			//ID錯誤
			$data = array(
				'code' => 0
			);
		}
		header('Content-type: application/json');
		echo CJSON::encode($data);
		Yii::app()->end();		
	}

	public function actionInvoiceReset()
	{
		if(isset($_POST['id'])){
			$model = SupplierApplicationLog::model()->findByPk($_POST['id']);
			if($model !== null){
				$model->invoice = "";
				$model->invoice_time = "";
				$model->invoice_by = 0;
				$model->status = 2;
				if($model->save()){
					//儲存成功
					$data = array(
						'code' => 1
					);	
				}else{
					//儲存失敗
					$data = array(
						'code' => 2
					);						
				}
			}else{
				//ID錯誤
				$data = array(
					'code' => 0
				);				
			}
		}else{
			//ID錯誤
			$data = array(
				'code' => 0
			);
		}
		header('Content-type: application/json');
		echo CJSON::encode($data);
		Yii::app()->end();		
	}

	public function actionInvoiceView()
	{
		if(isset($_POST['id'])){
			$model = SupplierApplicationLog::model()->findByPk($_POST['id']);

			$this->renderPartial('invoiceView',array(
				"model" => $model
			));	
		}else{
			throw new CHttpException(404,'不存在');
		}
	}

	public function actionSendBack()
	{
		if(isset($_POST['id'])){
			$model = SupplierApplicationLog::model()->findByPk($_POST['id']);
			if($model !== null){
				$model->status = 0;
				SupplierApplicationMonies::model()->updateAll(
					array(
						'application_type' => 0,
						'application_id' => $application->id,
						'application_by' => Yii::app()->user->id
					),
					'supplier_id = ' . $model->supplier_id
				);
				if($model->save()){
					//儲存成功
					$data = array(
						'code' => 1
					);	
				}else{
					//儲存失敗
					$data = array(
						'code' => 2
					);						
				}
			}else{
				//ID錯誤
				$data = array(
					'code' => 0
				);				
			}
		}else{
			//ID錯誤
			$data = array(
				'code' => 0
			);
		}
		header('Content-type: application/json');
		echo CJSON::encode($data);
		Yii::app()->end();
	}


}