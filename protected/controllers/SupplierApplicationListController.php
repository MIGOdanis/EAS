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
		if(isset($_GET['export']) && $_GET['export']){
			$this->exportSupplierApplication();
			Yii::app()->end();
		}		
		$accountsStatus = SiteSetting::model()->getValByKey("accounts_status");
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");

		$model = new SupplierApplicationLog('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierApplicationLog']))
			$model->attributes=$_GET['SupplierApplicationLog'];

		$this->render('admin',array(
			'model'=>$model,
			"accountsStatus" => $accountsStatus,
			"monthOfAccount" => $monthOfAccount
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
				$model->invoice_time = strtotime($_POST['invoiceTime']);
				$model->invoice_by = Yii::app()->user->id;
				$model->status = 3;
				if($model->save()){
					SupplierYearAccounts::model()->updateAll(
						array(
							'application_type' => 2,
						),
						'supplier_id = ' . $model->supplier_id . ' 
							AND application_year = "' . $model->year .'" 
							AND application_month = "' . $model->month .'"'
					);	
					DeductAccounts::model()->updateAll(
						array(
							'status' => 0
						),
						'supplier_id = ' . $model->supplier_id . ' AND status = 1'
						
					);					
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
					SupplierYearAccounts::model()->updateAll(
						array(
							'application_type' => 1,
						),
						'supplier_id = ' . $model->supplier_id . ' 
							AND application_year = "' . $model->year .'" 
							AND application_month = "' . $model->month .'"'
					);		
					DeductAccounts::model()->updateAll(
						array(
							'status' => 1,
						),
						'supplier_id = ' . $model->supplier_id . ' 
							AND application_year = "' . $model->year .'" 
							AND application_month = "' . $model->month .'"'
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
				SupplierYearAccounts::model()->updateAll(
					array(
						'application_type' => 0,
					),
					'supplier_id = ' . $model->supplier_id . ' AND application_type = 1'

				);
				DeductAccounts::model()->updateAll(
					array(
						'application_id' => "",
						'application_by' => "",
						'application_year' => "",
						'application_month' => "",
					),
					'supplier_id = ' . $model->supplier_id . ' AND status = 0'
					
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
					'code' => 4
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

	public function unTax($data){
		$tax = Yii::app()->params['taxType'][$data->supplier->type];
		if($data->supplier->type == 1)
			$tax = 1;

		return ($this->tax($data) / $tax);
	}


	public function tax($data){
		$tax = Yii::app()->params['taxType'][$data->supplier->type];
		if($data->supplier->type == 1)
			$tax = 1;

		return round($data->monies * $tax);
		
	}

	public function taxDeductTot($data){
		$tax = $this->tax($data);
		$taxDeduct = Yii::app()->params['taxTypeDeduct'][$data->supplier->type];

		if($data->supplier->type == 1 && $tax < 20000){
			$taxDeduct = 1;
		}

		return round($tax * $taxDeduct);
		
	}

	public function taxDeduct($data){
		$tax = $this->tax($data);
		$taxDeduct = $this->taxDeductTot($data);

		return round($tax - $taxDeduct);
	}

	public function exportSupplierApplication(){
		require dirname(__FILE__).'/../extensions/phpexcel/PHPExcel.php';
		if($_GET['year'] && $_GET['month']){

			$criteria = new CDbCriteria;
			$criteria->addCondition("year = " . $_GET['year']);
			$criteria->addCondition("month = " . $_GET['month']);
			$criteria->order = "supplier.tos_id DESC";
			$SupplierApplicationLog = SupplierApplicationLog::model()->with("supplier")->findAll($criteria);

			// print_r($SupplierApplicationLog); exit;

			$status = array(
				"已退回",
				"申請中",
				"憑證已確認",
				"申請已完成",
			);

			$reportName = array(
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'E86D4B'),
				),
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)

			);

			$title = array(
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'E8BE93'),
				),
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			);

			$title2 = array(
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'E8C7C7'),
				),
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			);

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("CLICKFORCE INC.")->setTitle("CLICKFORCE Supplier Report")
										 ->setSubject("CLICKFORCE Supplier Report")->setCategory("Report");

			$objPHPExcel->getActiveSheet()->mergeCells('A1:AA1');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '供應商請款報表(' . $_GET['year'] . " / " . $_GET['month'] . ')');

			$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->applyFromArray($reportName);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('B2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('D2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('E2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('F2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('I2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('J2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('K2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('L2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('M2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('N2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('O2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('P2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('Q2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('R2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('S2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('T2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('U2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('V2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('W2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('X2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('Y2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('Z2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('AA2')->applyFromArray($title);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '供應商ID');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '供應商名稱');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '供應商身分');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', '請款期間(起)');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', '請款期間(迄)');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', '請款狀態');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', '未稅金額');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', '含稅金額');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', '代扣稅額');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', '請款金額');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', '憑證格式');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', '憑證時間');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', '憑證號碼');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N2', '戶名(請款公司/個人)');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O2', '統編/身份證字號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P2', '連絡地址');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q2', '銀行名稱');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R2', '分行');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S2', '帳號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T2', '銀行代號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U2', '分行代號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V2', '分支機構代號/Swiftcode');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W2', '中間銀行 swift code');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X2', 'Email(主要聯絡人)');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y2', '國家代碼');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z2', '匯款日期');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA2', '請款年度');

			if($SupplierApplicationLog !== null){
				$r = 3;
				$total_monies = 0;
				$cril_total_monies = 0;
				foreach ($SupplierApplicationLog as $value) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('A' . $r, (string)$value->supplier->tos_id,PHPExcel_Cell_DataType::TYPE_STRING); //setCellValue('P' . $r, $value->supplier->account_number);												
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $r, $value->supplier->name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $r, Yii::app()->params['supplierType'][$value->supplier->type]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $r, date("Y-m",$value->start_time));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $r, date("Y-m",$value->end_time));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $r, $status[$value->status]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $r, number_format($this->unTax($value), 0, "." ,""));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $r, number_format($this->tax($value), 0, "." ,""));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $r, number_format($this->taxDeduct($value), 0, "." ,""));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $r, number_format($this->taxDeductTot($value), 0, "." ,""));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $r, Yii::app()->params["invoiceType"][$value->certificate_status]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $r, (empty($value->invoice_time)) ? "無資料" : date("Y-m-d",$value->invoice_time));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $r, $value->invoice);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $r, $value->supplier->account_name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('O' . $r, (string)$value->supplier->tax_id,PHPExcel_Cell_DataType::TYPE_STRING); //setCellValue('P' . $r, $value->supplier->account_number);						
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $r, $value->supplier->mail_address); //N AD
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $r, $value->supplier->bank_name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $r, $value->supplier->bank_sub_name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('S' . $r, (string)$value->supplier->account_number,PHPExcel_Cell_DataType::TYPE_STRING); //setCellValue('P' . $r, $value->supplier->account_number);						
						$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('T' . $r, (string)$value->supplier->bank_id,PHPExcel_Cell_DataType::TYPE_STRING); //setCellValue('P' . $r, $value->supplier->account_number);											
						$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('U' . $r, (string)$value->supplier->bank_sub_id,PHPExcel_Cell_DataType::TYPE_STRING); //setCellValue('P' . $r, $value->supplier->account_number);											
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $r, $value->supplier->bank_swift);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W' . $r, $value->supplier->bank_swift2);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X' . $r, $value->supplier->email);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y' . $r, $value->supplier->country_code);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z' . $r, date("Y-m-t", strtotime("+1 month", $value->end_time)));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA' . $r, date("Y",$value->end_time));
					
					// $total_monies += $value->total_monies;
					// $cril_total_monies += ceil($value->total_monies);
					$r++;
				}
				$r++;
				$objPHPExcel->getActiveSheet()->mergeCells('A' . $r . ':B' . $r . '');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $r, "資料時間 : " . date("Y-m-d H:i:s"));
				// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $r, $total_monies);
				// $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $r, $cril_total_monies);
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '沒有資料');
			}


	        $objPHPExcel->getActiveSheet()->setTitle("供應商請款報表");

			$criteria = new CDbCriteria;
			$criteria->addCondition("year = " . $_GET['year']);
			$criteria->addCondition("month = " . $_GET['month']);	
			$criteria->addCondition("t.status = 3");
			$log = SupplierApplicationLog::model()->findAll($criteria);
			$supplierByLog = array();
			foreach ($log as $value) {
				$supplierByLog[] = $value->supplier_id;
			}

			$criteria = new CDbCriteria;
			$criteria->addCondition("year = " . $_GET['year']);
			$criteria->addCondition("month = " . $_GET['month']);
			$criteria->addInCondition("supplier.tos_id", $supplierByLog);
			$criteria->order = "supplier.tos_id DESC";
			$supplierMoniesMonthlyByLog = SupplierMoniesMonthly::model()->with("supplier","site","adSpace")->findAll($criteria);

	        $objPHPExcel->createSheet();
	        $objPHPExcel->setActiveSheetIndex(1)->setCellValue('A1', '供應商對帳表 - 請款完成(' . $_GET['year'] . " / " . $_GET['month'] . ')');
	        $objPHPExcel->setActiveSheetIndex(1)->setTitle("供應商對帳表 - 請款完成");			

			$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A1:Q1');
			$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A2:I2');
			$objPHPExcel->setActiveSheetIndex(1)->mergeCells('J2:L2');
			$objPHPExcel->setActiveSheetIndex(1)->mergeCells('M2:O2');
			$objPHPExcel->setActiveSheetIndex(1)->mergeCells('P2:Q2');

			
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A2', '供應商資訊');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('J2', '拆帳方式');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('M2', '數據');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('P2', '結帳金額');

			$objPHPExcel->setActiveSheetIndex(1)->getStyle('A1')->applyFromArray($reportName);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('A2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('J2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('M2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('P2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('A3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('B3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('C3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('D3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('E3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('F3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('G3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('H3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('I3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('J3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('K3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('L3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('M3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('N3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('O3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('P3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('Q3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(1)->getStyle('R3')->applyFromArray($title2);

			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A3', '供應商ID');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B3', '供應商名稱');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C3', '供應商身份');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D3', '網站ID');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E3', '網站名稱');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F3', '網站類型');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G3', '版位ID');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('H3', '版位名稱');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('I3', '版位大小');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('J3', '採購類型');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('K3', '計費方法');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('L3', '價格');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('M3', 'IMP');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('N3', 'CLICK');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('O3', '媒體營收');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('P3', '原始金額');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('Q3', '未稅金額');
			$objPHPExcel->setActiveSheetIndex(1)->setCellValue('R3', '年度');

			if($supplierMoniesMonthlyByLog !== null){
				$r = 4;
				$total_monies = 0;
				$cril_total_monies = 0;
				$checkId = 0;
				foreach ($supplierMoniesMonthlyByLog as $value) {

					if($checkId != $value->supplier_id){ 
						$checkId = $value->supplier_id;
						$criteria = new CDbCriteria;
						$criteria->addCondition("supplier_id = " . $value->supplier_id);
						$criteria->addCondition("application_type = 2");
						$criteria->addCondition("application_year = " . $_GET['year']);
						$criteria->addCondition("application_month = " . $_GET['month']);
						$yearAccounts = SupplierYearAccounts::model()->findAll($criteria);	
						foreach ($yearAccounts as $year) {
							$criteria = new CDbCriteria;
							$criteria->addCondition("adSpace_id = " . $year->adSpace_id);
							$criteria->addCondition("year = " . $year->year);
							$criteria->order = "t.id DESC";
							$yearMoniesMonthly = SupplierMoniesMonthly::model()->find($criteria);

							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A' . $r, $yearMoniesMonthly->supplier_id);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B' . $r, $yearMoniesMonthly->supplier->name);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C' . $r, Yii::app()->params['supplierType'][$yearMoniesMonthly->supplier->type]);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D' . $r, $yearMoniesMonthly->site_id);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E' . $r, $yearMoniesMonthly->site->name);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F' . $r, Yii::app()->params["siteType"][$yearMoniesMonthly->site->type]);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G' . $r, $yearMoniesMonthly->adSpace_id);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('H' . $r, $yearMoniesMonthly->adSpace->name);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('I' . $r, ($yearMoniesMonthly->site->type == 1) ? $yearMoniesMonthly->adSpace->width . " x " . $yearMoniesMonthly->adSpace->height : str_replace (":"," x ",$yearMoniesMonthly->adSpace->ratio_id));
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('J' . $r, Yii::app()->params['buyType'][$yearMoniesMonthly->buy_type]);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('K' . $r, Yii::app()->params['chrgeType'][$yearMoniesMonthly->charge_type]);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('L' . $r, ($yearMoniesMonthly->price * Yii::app()->params['priceType'][$yearMoniesMonthly->charge_type]));
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('M' . $r, $yearMoniesMonthly->imp);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('N' . $r, $yearMoniesMonthly->click);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('O' . $r, $yearMoniesMonthly->total_monies);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('P' . $r, $yearMoniesMonthly->total_monies);
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('Q' . $r, ceil($yearMoniesMonthly->total_monies));
							$objPHPExcel->setActiveSheetIndex(1)->setCellValue('R' . $r, $yearMoniesMonthly->year);

							$total_monies += $yearMoniesMonthly->total_monies;
							$cril_total_monies += ceil($yearMoniesMonthly->total_monies);
							$r++;
						}
					}

					

					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A' . $r, $value->supplier_id);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B' . $r, $value->supplier->name);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C' . $r, Yii::app()->params['supplierType'][$value->supplier->type]);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D' . $r, $value->site_id);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E' . $r, $value->site->name);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F' . $r, Yii::app()->params["siteType"][$value->site->type]);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G' . $r, $value->adSpace_id);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('H' . $r, $value->adSpace->name);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('I' . $r, ($value->site->type == 1) ? $value->adSpace->width . " x " . $value->adSpace->height : str_replace (":"," x ",$value->adSpace->ratio_id));
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('J' . $r, Yii::app()->params['buyType'][$value->buy_type]);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('K' . $r, Yii::app()->params['chrgeType'][$value->charge_type]);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('L' . $r, ($value->price * Yii::app()->params['priceType'][$value->charge_type]));
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('M' . $r, $value->imp);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('N' . $r, $value->click);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('O' . $r, $value->total_monies);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('P' . $r, $value->total_monies);
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('Q' . $r, ceil($value->total_monies));
					$objPHPExcel->setActiveSheetIndex(1)->setCellValue('R' . $r, $value->year);

					$total_monies += $value->total_monies;
					$cril_total_monies += ceil($value->total_monies);
					$r++;
				}



				$objPHPExcel->setActiveSheetIndex(1)->setCellValue('P' . $r, $total_monies);
				$objPHPExcel->setActiveSheetIndex(1)->setCellValue('Q' . $r, $cril_total_monies);
			}else{
				$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A4', '沒有資料');
			}

	        $objPHPExcel->setActiveSheetIndex(0);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $_GET['year'] . "-" . $_GET['month'] . '供應商請款報表.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		}
	}



}