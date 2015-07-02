<?php

class SupplierApplicationMoniesController extends Controller
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
			$this->exportSupplierMoniesMonthly();
			Yii::app()->end();
		}
		$accountsStatus = SiteSetting::model()->getValByKey("accounts_status");

		$criteria = new CDbCriteria;
		$criteria->addCondition("name = 'lastCronUnapplicationMonies'");
		$lastSync = Log::model()->find($criteria);	

		$model = new SupplierApplicationMonies('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierApplicationMonies']))
			$model->attributes=$_GET['SupplierApplicationMonies'];

		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");


		$this->render('admin',array(
			'model'=>$model,
			'lastSync'=>$lastSync,
			"accountsStatus" => $accountsStatus,
			"monthOfAccount" => $monthOfAccount,
		));
	}


	public function actionApplication($id)
	{	
		$id = (int)$id;
		if(isset($id)){
			$criteria = new CDbCriteria;
			$criteria->select = '(sum(t.total_monies) + sum(t.month_monies)) as count_monies,
			 sum(t.total_monies) as total_monies,
			 sum(t.month_monies) as month_monies,
			 t.id as id,
			 t.supplier_id as supplier_id,
			 t.site_id as site_id,
			 t.this_application as this_application,
			 t.adSpace_id as adSpace_id,
			 t.total_monies as total_monies,
			 t.month_monies as month_monies,
			 t.last_application as last_application,
			 t.application_type as application_type,
			 t.application_id as application_id,
			 t.application_by as application_by,
			 t.create_time as create_time,
			 t.update_time as update_time';
			$criteria->addCondition("t.supplier_id = " . $id);
			$criteria->with = array("site",	 "site.supplier");
			$criteria->group = "t.supplier_id";	
			$model = SupplierApplicationMonies::model()->find($criteria);
			if($model !== null){
				$criteria = new CDbCriteria;
				$criteria->addCondition("supplier_id = " . $id);
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
					$application->supplier_id = $id;
					$application->lock = 1;
					$application->pay_time = 0;
					if($application->save()){
						SupplierApplicationMonies::model()->updateAll(
							array(
								'application_type' => 1,
								'application_id' => $application->id,
								'application_by' => Yii::app()->user->id
							),
							'supplier_id = ' . $id
						);
						header('Content-type: application/json');
						echo json_encode(array("code" => "1"));
						Yii::app()->end();
					}else{
						print_r($application->getErrors());
						header('Content-type: application/json');
						echo json_encode(array("code" => "2" , "msg" => "請款失敗，請聯繫管理人員 #2"));
						Yii::app()->end();
					}
				}	
				header('Content-type: application/json');
				echo json_encode(array("code" => "4" , "msg" => "請款失敗，請聯繫管理人員 #4"));
				Yii::app()->end();						
			}
			header('Content-type: application/json');
			echo json_encode(array("code" => "5" , "msg" => "請款失敗，請聯繫管理人員 #5"));
			Yii::app()->end();				
		}
		header('Content-type: application/json');
		echo json_encode(array("code" => "3" , "msg" => "請款失敗，請聯繫管理人員 #3"));
		Yii::app()->end();
	}

	public function exportSupplierMoniesMonthly(){
		require dirname(__FILE__).'/../extensions/phpexcel/PHPExcel.php';
		if($_GET['year'] && $_GET['month']){

			$criteria = new CDbCriteria;
			$criteria->addCondition("year = " . $_GET['year']);
			$criteria->addCondition("month = " . $_GET['month']);
			$supplierMoniesMonthly = SupplierMoniesMonthly::model()->with("supplier","site","adSpace")->findAll($criteria);

			//print_r($supplierMoniesMonthly); exit;

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

			$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
			$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
			$objPHPExcel->getActiveSheet()->mergeCells('H2:J2');
			$objPHPExcel->getActiveSheet()->mergeCells('K2:M2');
			$objPHPExcel->getActiveSheet()->mergeCells('N2:O2');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '供應商對帳表(' . $_GET['year'] . " / " . $_GET['month'] . ')');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '供應商資訊');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', '拆帳方式');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', '數據');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N2', '結帳金額');

			$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->applyFromArray($reportName);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('H2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('K2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('N2')->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('B3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('C3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('D3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('E3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('F3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('G3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('H3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('I3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('J3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('K3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('L3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('M3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('N3')->applyFromArray($title2);
			$objPHPExcel->setActiveSheetIndex(0)->getStyle('O3')->applyFromArray($title2);


			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '供應商ID');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '供應商名稱');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '供應商身份');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '網站ID');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '網站名稱');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '版位ID');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', '版位名稱');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '採購類型');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '計費方法');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '價格');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', 'IMP');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', 'CLICK');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', '媒體營收');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', '原始金額');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', '未稅金額');

			if($supplierMoniesMonthly !== null){
				$r = 4;
				$total_monies = 0;
				$cril_total_monies = 0;
				foreach ($supplierMoniesMonthly as $value) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $r, $value->supplier_id);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $r, $value->supplier->name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $r, Yii::app()->params['supplierType'][$value->supplier->type]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $r, $value->site_id);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $r, $value->site->name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $r, $value->adSpace_id);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $r, $value->adSpace->name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $r, Yii::app()->params['buyType'][$value->buy_type]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $r, Yii::app()->params['chrgeType'][$value->charge_type]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $r, ($value->price * Yii::app()->params['priceType'][$value->charge_type]));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $r, $value->imp);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $r, $value->click);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $r, $value->total_monies);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $r, $value->total_monies);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $r, ceil($value->total_monies));
					$total_monies += $value->total_monies;
					$cril_total_monies += ceil($value->total_monies);
					$r++;
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $r, $total_monies);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $r, $cril_total_monies);
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', '沒有資料');
			}


	        $objPHPExcel->getActiveSheet()->setTitle("供應商對帳表");

	        $objPHPExcel->setActiveSheetIndex(0);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $_GET['year'] . "-" . $_GET['month'] . '供應商對帳表.xlsx"');
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