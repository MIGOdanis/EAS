<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	
	public $layout='column2';

	public $nav=array();
	public $user;
	public $supplier;
	public $breadcrumbs=array();

	public function beforeAction($action=null){
		if(!isset($this->user) || empty($this->user))
			$this->user = User::model()->with("auth")->findByPk(Yii::app()->user->id);

		if(isset($this->user) && !empty($this->user) && $this->user->group == 7 && $this->user->supplier_id > 0)
			$this->supplier = Supplier::model()->findByPk($this->user->supplier_id);

		if(isset(Yii::app()->session['virtual_id']) && !empty(Yii::app()->session['virtual_id']))
			$this->supplier = Supplier::model()->findByPk((int)Yii::app()->session['virtual_id']);


		if(!isset($this->nav) || empty($this->nav))
			require dirname(__FILE__).'/../views/layouts/_set_menu.php';
		
		return true;
	}

	public function checkUserAuth($action=null){
		if(Yii::app()->user->id <= 0){
			$this->redirect(array("login/index"));
		}

		// /print_r(Yii::app()->user->id); exit;

		$auth = array();		
		$this->beforeAction();
		$ua = json_decode($this->user->auth->auth,true);

		foreach ($this->nav as $navIndex => $value) {
			if(in_array($this->id, $value['controllers'])){
				$auth = $ua[$navIndex][$this->id];
				break;
			}
		}
		
		if($this->id == "user"){
			$auth[] = "repassword";		
		}

		if (!is_array($auth)) {
			throw new CHttpException(403,'The requested page does not exist.');
		}
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=> $auth,
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function checkSupplierAuth($action=null){
		$auth = array();
		$this->beforeAction();
	
		if(Yii::app()->user->id <= 0){
			$this->redirect(array("login/index"));
		}

		if($this->user->group != 7){
			$ua = json_decode($this->user->auth->auth,true);
			// print_r($ua['tosSupplier']['tosSupplier']); exit;
			if(!in_array("gotoDashboard", $ua['tosSupplier']['tosSupplier']))
				throw new CHttpException(403,'The requested page does not exist.');
		}

		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				// 'actions'=> $auth,
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}




	// 使用者啟用停用
	public function actionActive($id)
	{
		$model=$this->loadModel($id);
        $model->scenario = 'active';
		$model->active = ($model->active == 1) ? 0 : 1;
		if($model->save())
			echo 1;
			if(isset($_GET['redirect']))
				$this->redirect(array($_GET['redirect']));
		else
			echo 'Error';
	}

	//log
	public function writeLog($str,$dir,$fileName){
		if (!is_dir(dirname(__FILE__) . "/../../logs/" . $dir)){     //檢察upload資料夾是否存在
			mkdir(dirname(__FILE__) . "/../../logs/" . $dir, 0755, true);
		}
		$path = dirname(__FILE__) . "/../../logs/" . $dir . "/" .$fileName;
		$type = (is_file($path)) ? "a+" : "w+";
		$file = fopen($path,$type);
		$content = date("Y-m-d H:i:s") . " | " . $str . "\r\n";
	    fwrite($file,$content);
	    fclose($file);	
	}

	public function saveLog($name,$value){
		$criteria = new CDbCriteria;
		$criteria->addCondition("name = '" . $name . "'");
		$model = Log::model()->find($criteria);
		if($model === null)
			$model = new Log();
		$model->name = $name;
		$model->value = $value;
		$model->save();
	}		

	public function email($address, $subject, $message)
	{
		$mailer = Yii::createComponent('application.extensions.mailer.EMailer');
		$mailer->Host = Yii::app()->params['mail']['smtpHost'];
		$mailer->SMTPAuth = Yii::app()->params['mail']['smtpAuth'];
		$mailer->Username = Yii::app()->params['mail']['smtpUsername'];
		$mailer->Password = Yii::app()->params['mail']['smtpPassword'];
		$mailer->IsSMTP();
        $mailer->IsHTML(true);
		$mailer->From = Yii::app()->params['mail']['adminEmail'];
		$mailer->AddReplyTo(Yii::app()->params['mail']['adminEmail']);
		$mailer->AddAddress($address);
		$mailer->FromName = Yii::app()->params['mail']['adminName'];
		$mailer->CharSet = 'UTF-8';
		$mailer->Subject = Yii::t('demo', $subject);
		$mailer->Body = $message;
		return $mailer->Send();	
	}

	/**
	 * 上傳圖片(縮圖)
	 * 
	 * @param array $image 檔案欄位
	 * @param array('width','height') $resize 縮圖大小 ***不進行縮圖可設置為 false ***
	 * @param string $upload_folder 上傳目錄(沒有時自動建立)
	 * @param string $filename 縮圖後名稱
	 * @param boolean $o_img 是否存留原圖
	 * @param string $o_name 原圖名稱
	 * @param int $filesize 大小限制 (單位mb)
	 *	宣告範例
	 * 	$this->upload_image_resize(
	 *		$_FILES['file'],
	 *		array('width'=>200,'height'=>200),
	 *		Yii::getPathOfAlias('upload') . '/user/' . Yii::app()->user->id,
	 *		"m_profile_image",
	 *		true,
	 *		"o_profile_image",
	 * 		2
	 * 	);
	 */
	public function upload_image_resize($image,$resize,$upload_folder,$filename,$o_img=false,$o_name=o_image,$filesize=4,$corp=false)
	{

		if(empty($image['tmp_name']))
			return;

		if($image['size']/1024000 > $filesize)
			return "您的圖檔超過大小限制 (" . $filesize . " MB)";

        if(!is_dir($upload_folder)){
            mkdir($upload_folder, 0777, true);
        }
        
		//根據不同格式取得圖檔
		switch (strtolower($image['type'])) {
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/pjpeg':
				$src = @imagecreatefromjpeg($image['tmp_name']);
				if(!$src){
					return "您的圖檔原始格式可能不是JPG";
				}
				break;
			case 'image/png':
				$outputType = "png";
				$src = imagecreatefrompng($image['tmp_name']);
				break;
			// case 'image/gif':
			// 	$src = imagecreatefromgif($image['tmp_name']);
			// 	break;
			default:
				return "不支援您的圖檔格式(支援JPG格式)";
		}
		
		// 取得來源圖片長寬
		$src_w = imagesx($src);
		$src_h = imagesy($src);
		if( ($src_w > $resize['width'] || $src_h > $resize['height']) && $resize !== false){
			//裁圖計算區域
			if($corp){
				if($src_w > $src_h){
					$corp_x =intval (($src_w - $src_h) / 2)  ;
					$corp_y = 0;
					$src_w = $src_h;
				}else{
					$corp_y = intval(($src_h - $src_w) / 2)  ;
					$corp_x = 0;
					$src_h = $src_w;
				}
			}
			//自動縮圖
			if($src_w > $src_h){
				$percent =  $resize['width'] / $src_w;
				$new_w = $src_w * $percent;
				$new_h = $src_h * $percent;
			}else{
				$percent = $resize['height'] / $src_h;
				$new_w = $src_w * $percent;
				$new_h = $src_h * $percent;
			}

			$thumb = imagecreatetruecolor($new_w,$new_h);

			if($outputType == "png"){
				//產生透明背景
				imagecolorallocatealpha($thumb , 0 , 0 , 0 ,127);
				//關閉混合模式，以便透明顏色能覆蓋原畫布
				imagealphablending($thumb ,false);
				imagecopyresampled($thumb, $src, 0, 0, 0, 0,$new_w,$new_h, $src_w, $src_h);
				//產生透明色背景的png圖片
				imagesavealpha($thumb, true);
				ImagePng($thumb,$upload_folder."/".$filename);
			}else{
				$white = imagecolorallocate($thumb, 255, 255, 255);
				imagefill($thumb,0,0,$white);
				if($corp){
					imagecopyresampled($thumb, $src, 0, 0, $corp_x ,$corp_y,$resize['width'],$resize['height'], $src_w, $src_h);
				}else{
					//開啟裁圖在第5第6個值放置$corp_x ,$corp_y  $dst_x, $dst_y設置為0 $new_w,$new_h設置為$resize['width'],$resize['height']
					imagecopyresampled($thumb, $src, 0, 0, 0, 0,$new_w,$new_h, $src_w, $src_h);
				}

				imagejpeg($thumb,$upload_folder."/".$filename);
			}
			
		}else{
			copy($image['tmp_name'],$upload_folder."/".$filename);

		}
		
		if($o_img)
			copy($image['tmp_name'],$upload_folder."/".$o_name); 
		
		return true;
	}	

	public function exportExcel($Report){
		require dirname(__FILE__).'/../extensions/phpexcel/PHPExcel.php';

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

		$objPHPExcel->getActiveSheet()->mergeCells('A1:' . $Report['width']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $Report['titleName']);
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->applyFromArray($reportName);
		foreach($Report['title'] as $position => $row){
			$objPHPExcel->setActiveSheetIndex(0)->getStyle($position)->applyFromArray($title);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($position, $row);
		}

			if($Report['data'] !== null){
				$r = 3;
				foreach ($Report['data'] as $data) {
					foreach ($data as $position => $value) {
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($position . $r, $value);
					}
					$r++;
				}
				$r++;
				$objPHPExcel->getActiveSheet()->mergeCells('A' . $r .':D' . $r);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $r, "報表時間 :" . date("Y-m-d H:i:s"));
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '沒有資料');
			}


	        $objPHPExcel->getActiveSheet()->setTitle($Report['name']);

	        $objPHPExcel->setActiveSheetIndex(0);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'. $Report['fileName'] . date("Ymd-his") . '.xlsx"');
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

	public function getDay(){

		if(!isset($_GET) || $_GET['type'] == "yesterday"){
			$startDay = date("Y-m-d",strtotime('-1 day'));
			$endDay = date("Y-m-d",strtotime('-1 day')); 
		}


		if($_GET['type'] == "7day"){
			$startDay = date("Y-m-d",strtotime('-7 day'));
			$endDay = date("Y-m-d"); 
		}

		if($_GET['type'] == "30day"){
			$startDay = date("Y-m-d",strtotime('-30 day'));
			$endDay = date("Y-m-d"); 			
		}

		if($_GET['type'] == "pastMonth"){
			$startDay = date("Y-m-01",strtotime("-1 Months"));
			$endDay = date("Y-m-t",strtotime("-1 Months")); 			
		}

		if($_GET['type'] == "thisMonth"){
			$startDay = date("Y-m-01");
			$endDay = date("Y-m-t"); 	
		}	

		if($_GET['type'] == "custom"){
			if(isset($_GET['startDay']) && !empty($_GET['startDay'])){
				$startDay = date("Y-m-d",strtotime($_GET['startDay'] . "00:00:00"));
			}
			if(isset($_GET['endDay']) &&  !empty($_GET['endDay'])){
				$endDay = date("Y-m-d",strtotime($_GET['endDay'] . "00:00:00"));
			}			
		}

		return array($startDay,$endDay);
	}

	public function afterSupplierUpdate($model){

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
		$savelog->bank_swift2 = $model->bank_swift2;
		$savelog->remark = $model->remark;
		$savelog->create_time = $model->create_time;
		$savelog->update_by = Yii::app()->user->id;
		$savelog->update_time = time();
		$savelog->certificate_image = $model->certificate_image;
		$savelog->bank_book_img = $model->bank_book_img;
		$savelog->save();

	}

}