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

	public function tax($value){
		$tax = Yii::app()->params['taxType'][$value->supplier->type];
		if($value->supplier->type == 1 && $value->monies < 20000)
			$tax = 1;

		return $value->monies * $tax;
		
	}

	public function taxDeductTot($value){
		$tax =  $this->tax($value);
		$taxDeduct = Yii::app()->params['taxTypeDeduct'][$value->supplier->type];
		if($value->supplier->type == 1 && $value->monies >= 20000)
			$taxDeduct = 0.9;

		return $tax * $taxDeduct;
		
	}

	public function taxDeduct($value){
		$tax =  $this->tax($value);
		$taxDeduct = $this->taxDeductTot($value);

		return $tax - $taxDeduct;
		
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

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("CLICKFORCE INC.")->setTitle("CLICKFORCE Supplier Report")
										 ->setSubject("CLICKFORCE Supplier Report")->setCategory("Report");

			$objPHPExcel->getActiveSheet()->mergeCells('A1:V1');

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
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', '發票(憑證)號碼');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', '戶名(請款公司/個人)');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M2', '統編/身份證字號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N2', '銀行名稱');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O2', '分行');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P2', '帳號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q2', '銀行代號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R2', '分行代號');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S2', '分支機構代號/Swiftcode');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T2', '中間銀行 swift code');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U2', 'Email(主要聯絡人)');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V2', '國家代碼');

			if($SupplierApplicationLog !== null){
				$r = 3;
				$total_monies = 0;
				$cril_total_monies = 0;
				foreach ($SupplierApplicationLog as $value) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $r, $value->supplier->tos_id);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $r, $value->supplier->name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $r, Yii::app()->params['supplierType'][$value->supplier->type]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $r, date("Y-m",$value->start_time));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $r, date("Y-m",$value->end_time));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $r, $status[$value->status]);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $r, number_format($value->monies, 2, "." ,","));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $r, number_format($this->tax($value), 0, "." ,","));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $r, number_format($this->taxDeduct($value), 0, "." ,","));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $r, number_format($this->taxDeductTot($value), 0, "." ,","));
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $r, $value->invoice);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $r, $value->supplier->account_name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $r, $value->supplier->tax_id);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $r, $value->supplier->bank_name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $r, $value->supplier->bank_sub_name);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('P' . $r, (string)$value->supplier->account_number,PHPExcel_Cell_DataType::TYPE_STRING); //setCellValue('P' . $r, $value->supplier->account_number);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $r, $value->supplier->bank_id);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $r, $value->supplier->bank_sub_id);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S' . $r, $value->supplier->bank_swift);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T' . $r, $value->supplier->bank_swift2);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U' . $r, $value->supplier->email);
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V' . $r, $value->supplier->country_code);
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