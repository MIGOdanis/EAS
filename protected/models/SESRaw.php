<?php

//載入AWS套件
require dirname(__FILE__) . "/../../aws/aws-autoloader.php";
require "Mail/mime.php";
use Aws\Common\Enum\Region;
use Aws\Ses\SesClient;
class SESRaw extends CModule
{
	public static function sendMail($Source,$SourceName,$ToAddresses,$BccAddresses = null,$Subject,$Body,$file = null,$fileMime=null){
		//設置收件者
		$Destination = array();
		$Destination['ToAddresses'] = array($ToAddresses);
		if(!empty($BccAddresses))
			$Destination['BccAddresses'] = array($BccAddresses);
		//宣告SesClient
		$client = SesClient::factory(array(
			'key'    => 'AKIAJMENQ46ONQVPNZOA', //SMTP申請時取得的KEY
			'secret' => 'VtsxS/IukuOVyvt90z/LSes/QFFJWDUtSezRbiMD', //SMTP申請時取得的secret
		    'region' => Region::US_WEST_2 //地區名稱可見Aws\Common\Enum\Region.php
		));
	 
		$mail_mime = new Mail_mime(array('eol' => "\n")); 
		$mail_mime->setHTMLBody($Body); 
		if($file != null && $fileMime != null){
			$mail_mime->addAttachment($file, $fileMime);
		}

		$body = $mail_mime->get(array('html_charset' => 'utf-8'));

		$headerParams = array('From' => $Source.'(=?UTF-8?B?'.base64_encode($SourceName).'?=)' , 'To' => $ToAddresses, 'Subject' => $Subject);
		if($BccAddresses){
			$headerParams['Cc'] = $BccAddresses;
		}
		$headers = $mail_mime->txtHeaders($headerParams);	
		
		$message = $headers . "\r\n" .$body;

		try{
			$result = $client->sendRawEmail(
				array('RawMessage' =>
					array("Data" => base64_encode($message))
				)
			);

			//save the MessageId which can be used to track the request
			$msg_id = $result->get('MessageId');

		} catch (Exception $e) {
			//An error happened and the email did not get sent
			if($_GET['type'] == "test") {
				//測試模式時失敗echo mail 地址
				echo "發送到 : " . $ToAddresses . "的郵件發送測試失敗<br>";
			}	
			return array("send"=>false,"msg"=>$e->getMessage());
		}

		return array("send"=>true,"msg"=>$msg_id);
	}
}
