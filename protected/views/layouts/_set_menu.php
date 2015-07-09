<?php
$this->nav = array(
	"user" => array(
		"title" => "使用者管理",
		"url"=>"user/admin",
		"controllers" => array("user","authGroup","userAuth"),
		"list" => array(
			"user" => array(
				"title" => "使用者管理",
				"url"=>"user/admin",
				"action" => array(
					"admin" => "使用者清單",
					"update" => "更新使用者",
					"create" => "新增使用者",
					"active" => "停用/啟用使用者",
					"view" => "檢視使用者資料"
				),
			),
			"userAuth" => array(
				"title" => "使用者權限修改",
				"url"=>"userAuth/admin",
				"action" => array(
					"admin" => "使用者清單",
					"update" => "更新使用者權限",
				),
			),					
			"authGroup" => array(
				"title" => "權限組管理",
				"url"=>"authGroup/admin",
				"action" => array(
					"admin" => "權限組清單",
					"update" => "更新權限組",
					"create" => "新增權限組",
					"active" => "停用/啟用權限組",
					"view" => "檢視權限組資料"
				),
			)
		)
	),
	"tosSupplier" => array(
		"title" => "供應商管理",
		"url"=>"tosSupplier/admin",
		"controllers" => array("tosSupplier","tosSite","tosAdSpace","supplierRegister"),
		"list" => array(
			"supplierRegister" => array(
				"title" => "供應商電子合約管理",
				"url"=>"supplierRegister/admin",
				"action" => array(
					"admin" => "供應商電子合約清單",
					"view" => "供應商電子合約檢視",
					"check" => "供應商電子合約審核",
				),
			),
			"tosSupplier" => array(
				"title" => "供應商管理",
				"url"=>"tosSupplier/admin",
				"action" => array(
					"admin" => "供應商清單",
					"view" => "檢視供應商",
					"update" => "編輯供應商", 
					"supplierUserList" => "查詢供應商帳號清單",
					"supplierUserCreate" => "新建供應商帳號",
					"updateLog" => "查詢供應商編輯記錄",
					"updateLogView" => "檢視供應商編輯記錄",
					"gotoDashboard" => "模擬供應商前台"
				),
			),
			"tosSite" => array(
				"title" => "供應商網站管理",
				"url"=>"tosSite/admin",
				"action" => array(
					"admin" => "供應商網站清單",
					"view" => "檢視供應商網站"
				),
			),
			"tosAdSpace" => array(
				"title" => "供應商網站版位管理",
				"url"=>"tosAdSpace/admin",
				"action" => array(
					"admin" => "供應商網站版位清單",
					"view" => "檢視供應商網站版位"
				),
			),						
		)
	),
	"supplierApplicationMonies" => array(
		"title" => "供應商請款管理",
		"url"=>"supplierApplicationMonies/admin",
		"controllers" => array("supplierApplicationMonies", "supplierApplicationList", "accountsStatus", "supplierApplicationLog"),
		"list" => array(
			"supplierApplicationMonies" => array(
				"title" => "供應商請款管理",
				"url"=>"supplierApplicationMonies/admin",
				"action" => array(
					"admin" => "供應商清單",
					"application" => "供應商申請請款"
				),
			),
			"supplierApplicationList" => array(
				"title" => "本期請款管理",
				"url"=>"supplierApplicationList/admin",
				"action" => array(
					"admin" => "本期請款清單",
					"certificate" => "憑證確認",
					"invoice" => "填寫發票",
					"invoiceReset" => "重設發票",
					"InvoiceView" => "檢視發票",
					"sendBack" => "申請退回",
				),
			),
			"supplierApplicationLog" => array(
				"title" => "請款紀錄查詢",
				"url"=>"supplierApplicationLog/admin",
				"action" => array(
					"admin" => "請款紀錄查詢",
					"InvoiceView" => "檢視發票",
				),
			),
			"accountsStatus" => array(
				"title" => "開關帳設定",
				"url"=>"accountsStatus/admin",
				"action" => array(
					"admin" => "開關帳設定",
				),
			),						
		)
	),
	"mediaReport" => array(
		"title" => "報表查詢",
		"url"=>"mediaReport/supplierReport",
		"controllers" => array("mediaReport","advertiserReport"),
		"list" => array(
			"mediaReport" => array(
				"title" => "供應商日報",
				"url"=>"mediaReport/supplierReport",
				"action" => array(
					"supplierReport" => "供應商日報表",
					"siteReport" => "供應商網站日報表",
					"adSpaceReport" => "供應商網站版位日報表",
				),
			),
			"advertiserReport" => array(
				"title" => "訂單日報",
				"url"=>"advertiserReport/categoryReport",
				"action" => array(
					"categoryReport" => "訂單媒體分類報表",
					"campaignBannerReport" => "訂單總表"

				),					
			)						
		)
	),	
);
?>