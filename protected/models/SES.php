<?php

//載入AWS套件
require dirname(__FILE__) . "/../../aws/aws-autoloader.php";
use Aws\Common\Enum\Region;
use Aws\Ses\SesClient;
class SES extends CModule
{
	public static function sendMail($Source,$SourceName,$ToAddresses,$BccAddresses = null,$Subject,$Body){
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
	 
		$mailArray = array(
		    // 設定寄件人 (須為已驗證帳號) 
		    'Source' => $Source.'(=?UTF-8?B?'.base64_encode($SourceName).'?=)',
		    // 設定收件人
		    'Destination' => $Destination,
		    // 信件文本設定
		    'Message' => array(
		        // 標頭
		        'Subject' => array(
		            // 標題設定
		            'Data' => $Subject,
		            'Charset' => 'UTF-8',
		        ),
		        // 內文
		        'Body' => array(
		            'Html' => array(
		                // HTML區塊
		                'Data' => $Body,
		                'Charset' => 'UTF-8',
		            ),
		        ),
		    ),
		    //回復文件至何處
		    'ReplyToAddresses' => array($Source),
		    //寄件失敗時的通知信至何處
		    'ReturnPath' => Yii::app()->params['epReturnPath'],
		);

		try{
			$result = $client->sendEmail($mailArray);

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
