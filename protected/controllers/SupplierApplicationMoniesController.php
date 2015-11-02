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

			//製成供應商對帳表只有本月數字(SupplierApplicationMonies)
			$criteria = new CDbCriteria;
			// $criteria->addCondition("supplierMoniesMonthly.imp > 0");
			$criteria->addCondition("supplierMoniesMonthly.year = " . $_GET['year']);
			$criteria->addCondition("supplierMoniesMonthly.month = " . $_GET['month']);
			$criteria->order = "supplier.tos_id DESC";
			$supplierMoniesMonthly = SupplierApplicationMonies::model()->with("supplier","site","adSpace","supplierMoniesMonthly")->findAll($criteria);

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("CLICKFORCE INC.")->setTitle("CLICKFORCE Supplier Report")
										 ->setSubject("CLICKFORCE Supplier Report")->setCategory("Report");

	        $objPHPExcel = $this->makeSheetData($objPHPExcel, 0, $supplierMoniesMonthly);
	        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '供應商對帳表(' . $_GET['year'] . " / " . $_GET['month'] . ')');
	        $objPHPExcel->setActiveSheetIndex(0)->setTitle("供應商對帳表");

	        $objPHPExcel->setActiveSheetIndex(0);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $_GET['year'] . "-" . $_GET['month'] . '供應商對帳表.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: ' . date("D, d M Y H:i:s") . ' TST'); // Date in the past 
			header ('Last-Modified: '.date('D, d M Y H:i:s').' TST'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		}
	}

	public function makeSheetData($obj, $sheet, $data){

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

		$obj->setActiveSheetIndex($sheet)->mergeCells('A1:Q1');
		$obj->setActiveSheetIndex($sheet)->mergeCells('A2:I2');
		$obj->setActiveSheetIndex($sheet)->mergeCells('J2:L2');
		$obj->setActiveSheetIndex($sheet)->mergeCells('M2:O2');
		$obj->setActiveSheetIndex($sheet)->mergeCells('P2:Q2');

		
		$obj->setActiveSheetIndex($sheet)->setCellValue('A2', '供應商資訊');
		$obj->setActiveSheetIndex($sheet)->setCellValue('J2', '拆帳方式');
		$obj->setActiveSheetIndex($sheet)->setCellValue('M2', '數據');
		$obj->setActiveSheetIndex($sheet)->setCellValue('P2', '結帳金額');

		$obj->setActiveSheetIndex($sheet)->getStyle('A1')->applyFromArray($reportName);
		$obj->setActiveSheetIndex($sheet)->getStyle('A2')->applyFromArray($title);
		$obj->setActiveSheetIndex($sheet)->getStyle('J2')->applyFromArray($title);
		$obj->setActiveSheetIndex($sheet)->getStyle('M2')->applyFromArray($title);
		$obj->setActiveSheetIndex($sheet)->getStyle('P2')->applyFromArray($title);
		$obj->setActiveSheetIndex($sheet)->getStyle('A3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('B3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('C3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('D3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('E3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('F3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('G3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('H3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('I3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('J3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('K3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('L3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('M3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('N3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('O3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('P3')->applyFromArray($title2);
		$obj->setActiveSheetIndex($sheet)->getStyle('Q3')->applyFromArray($title2);

		$obj->setActiveSheetIndex($sheet)->setCellValue('A3', '供應商ID');
		$obj->setActiveSheetIndex($sheet)->setCellValue('B3', '供應商名稱');
		$obj->setActiveSheetIndex($sheet)->setCellValue('C3', '供應商身份');
		$obj->setActiveSheetIndex($sheet)->setCellValue('D3', '網站ID');
		$obj->setActiveSheetIndex($sheet)->setCellValue('E3', '網站名稱');
		$obj->setActiveSheetIndex($sheet)->setCellValue('F3', '網站類型');
		$obj->setActiveSheetIndex($sheet)->setCellValue('G3', '版位ID');
		$obj->setActiveSheetIndex($sheet)->setCellValue('H3', '版位名稱');
		$obj->setActiveSheetIndex($sheet)->setCellValue('I3', '版位大小');
		$obj->setActiveSheetIndex($sheet)->setCellValue('J3', '採購類型');
		$obj->setActiveSheetIndex($sheet)->setCellValue('K3', '計費方法');
		$obj->setActiveSheetIndex($sheet)->setCellValue('L3', '價格');
		$obj->setActiveSheetIndex($sheet)->setCellValue('M3', 'IMP');
		$obj->setActiveSheetIndex($sheet)->setCellValue('N3', 'CLICK');
		$obj->setActiveSheetIndex($sheet)->setCellValue('O3', '媒體營收');
		$obj->setActiveSheetIndex($sheet)->setCellValue('P3', '原始金額');
		$obj->setActiveSheetIndex($sheet)->setCellValue('Q3', '未稅金額');

		if($data !== null){
			$r = 4;
			$total_monies = 0;
			$cril_total_monies = 0;
			foreach ($data as $value) {

				$obj->setActiveSheetIndex($sheet)->setCellValue('A' . $r, $value->supplier_id);
				$obj->setActiveSheetIndex($sheet)->setCellValue('B' . $r, $value->supplier->name);
				$obj->setActiveSheetIndex($sheet)->setCellValue('C' . $r, Yii::app()->params['supplierType'][$value->supplier->type]);
				$obj->setActiveSheetIndex($sheet)->setCellValue('D' . $r, $value->site_id);
				$obj->setActiveSheetIndex($sheet)->setCellValue('E' . $r, $value->site->name);
				$obj->setActiveSheetIndex($sheet)->setCellValue('F' . $r, Yii::app()->params["siteType"][$value->site->type]);
				$obj->setActiveSheetIndex($sheet)->setCellValue('G' . $r, $value->adSpace_id);
				$obj->setActiveSheetIndex($sheet)->setCellValue('H' . $r, $value->adSpace->name);
				$obj->setActiveSheetIndex($sheet)->setCellValue('I' . $r, ($value->site->type == 1) ? $value->adSpace->width . " x " . $value->adSpace->height : str_replace (":"," x ",$value->adSpace->ratio_id));
				$obj->setActiveSheetIndex($sheet)->setCellValue('J' . $r, Yii::app()->params['buyType'][$value->supplierMoniesMonthly->buy_type]);
				$obj->setActiveSheetIndex($sheet)->setCellValue('K' . $r, Yii::app()->params['chrgeType'][$value->supplierMoniesMonthly->charge_type]);
				$obj->setActiveSheetIndex($sheet)->setCellValue('L' . $r, ($value->supplierMoniesMonthly->price * Yii::app()->params['priceType'][$value->supplierMoniesMonthly->charge_type]));
				$obj->setActiveSheetIndex($sheet)->setCellValue('M' . $r, $value->supplierMoniesMonthly->imp);
				$obj->setActiveSheetIndex($sheet)->setCellValue('N' . $r, $value->supplierMoniesMonthly->click);
				$obj->setActiveSheetIndex($sheet)->setCellValue('O' . $r, $value->month_monies);
				$obj->setActiveSheetIndex($sheet)->setCellValue('P' . $r, $value->month_monies);
				$obj->setActiveSheetIndex($sheet)->setCellValue('Q' . $r, ceil($value->month_monies));

				$month_monies += $value->month_monies;
				$cril_total_monies += ceil($value->month_monies);
				$r++;
			}
			$obj->setActiveSheetIndex($sheet)->setCellValue('P' . $r, $month_monies);
			$obj->setActiveSheetIndex($sheet)->setCellValue('Q' . $r, $cril_total_monies);
		}else{
			$obj->setActiveSheetIndex($sheet)->setCellValue('A4', '沒有資料');
		}
		
		return $obj;
	}

}