<?php

class SyncController extends Controller
{

	public $noPayCampaign;

	public function actionSyncSupplier()
	{
		set_time_limit(0);
		$criteria = new CDbCriteria;
		$criteria->addCondition("account_id = 2");
		$tosSupplier = TosCoreSupplier::model()->findAll($criteria);
		foreach ($tosSupplier as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = Supplier::model()->find($criteria);
			if($model === null){
				$model = new Supplier();
				$type = "creat";
			}
			
			$model = $this->mappingSupplier($model,$value);
			if(!$model->save()){
				print_r($model->getErrors()); exit;
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"syncSupplier/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"syncSupplier/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncSupplier",time());
	}

	public function mappingSupplier($model,$tosSupplier){
		$model->tos_id = $tosSupplier->id;
		$model->name = $tosSupplier->name;
		$model->contacts = $tosSupplier->contacts;
		$model->tel = $tosSupplier->tel;
		$model->email = $tosSupplier->email;
		$model->contacts_moblie = $tosSupplier->mobile;
		$model->company_name = $tosSupplier->company_name;
		$model->company_address = $tosSupplier->company_address;
		$model->create_time = strtotime($tosSupplier->create_time);
		$model->remark = $tosSupplier->remark;
		$model->sync_time = time();
		$model->status = $tosSupplier->status;
		return $model;
	}

	public function actionSyncSite()
	{
		set_time_limit(0);
		$criteria = new CDbCriteria;
		$criteria->addCondition("account_id = 2");

		if(isset($_GET['id']))
			$criteria->addCondition("id = " . $_GET['id']);
		
		$tosSite = TosCoreSite::model()->findAll($criteria);
		foreach ($tosSite as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = Site::model()->find($criteria);
			if($model === null){
				$model = new Site();
				$type = "creat";
			}
			
			$model = $this->mappingSite($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"syncSite/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"syncSite/run",
					date("YmdH") . "log.log"
				);
			}
			
		}
		$this->saveLog("lastSyncSite",time());
	}

	public function mappingSite($model,$tosSite){
		$model->tos_id = $tosSite->id;
		$model->name = $tosSite->name;
		$model->supplier_id = $tosSite->supplier_id;
		$model->type = $tosSite->type;
		$model->domain = $tosSite->domain;
		$model->description = $tosSite->description;
		$model->create_time = strtotime($tosSite->create_time);
		$model->sync_time = time();
		$model->status = $tosSite->status;

		return $model;
	}

	public function actionSyncAdSpace()
	{
		set_time_limit(0);
		$criteria = new CDbCriteria;
		$criteria->addCondition("account_id = 2");
		$tosAdSpace = TosCoreAdSpace::model()->findAll($criteria);
		foreach ($tosAdSpace as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = AdSpace::model()->find($criteria);
			if($model === null){
				$model = new AdSpace();
				$type = "creat";
			}
			
			$model = $this->mappingAdSpace($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncAdSpace/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncAdSpace/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncAdSpace",time());
	}

	public function mappingAdSpace($model,$tosAdSpace){

		$model->tos_id = $tosAdSpace->id;
		$model->site_id = $tosAdSpace->site_id;
		$model->name = $tosAdSpace->name;
		$model->status = $tosAdSpace->status;
		$model->type = $tosAdSpace->type;
		$model->ad_format = $tosAdSpace->ad_format;
		$model->ratio_id = $tosAdSpace->ratio_id;
		$model->def_creative_option = $tosAdSpace->def_creative_option;
		$model->def_creative_id = $tosAdSpace->def_creative_id;
		$model->adv_feature = $tosAdSpace->adv_feature;
		$model->material_format = $tosAdSpace->material_format;
		$model->buy_type = $tosAdSpace->buy_type;
		$model->charge_type = $tosAdSpace->charge_type;
		$model->price = $tosAdSpace->price;
		$model->create_time = strtotime($tosAdSpace->create_time);
		$model->alias = $tosAdSpace->alias;
		$model->description = $tosAdSpace->description;
		$model->source = $tosAdSpace->source;
		$model->width = $tosAdSpace->width;
		$model->height = $tosAdSpace->height;
		$model->sync_time = time();

		return $model;
	}

	public function actionSyncBuyReportDailyPcByDay()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$criteria = new CDbCriteria;
		$criteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = TosCoreCampaign::model()->findAll($criteria);
		$this->noPayCampaign = array();
		foreach ($noPayCampaign as $value) {
			$this->noPayCampaign[] = $value->id;
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("settled_time = '" . $_GET['day'] . "'");
		$buyReportDailyPc = TosTreporBuyDrDisplayDailyPcReport::model()->findAll($criteria);
		
		foreach ($buyReportDailyPc as $value) {
			$lastTime = $value->settled_time;
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$criteria->addCondition("report_type = 1");
			$model = BuyReportDailyPc::model()->find($criteria);
			if($model === null){
				$model = new BuyReportDailyPc();
				$type = "creat";
			}
			
			$model = $this->mappingBuyReportDailyPc($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyPcByday/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyPcByday/run",
					date("YmdH") . "log.log"
				);
			}
		}
	}

	public function actionSyncBuyReportDailyPc()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$lastTimeLog = Log::model()->getValByName("lastSyncBuyReportDailyPcTosTime");

		$criteria = new CDbCriteria;
		$criteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = TosCoreCampaign::model()->findAll($criteria);
		$this->noPayCampaign = array();
		foreach ($noPayCampaign as $value) {
			$this->noPayCampaign[] = $value->id;
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("settled_time > '" . $lastTimeLog . "'");
		$buyReportDailyPc = TosTreporBuyDrDisplayDailyPcReport::model()->findAll($criteria);
		
		foreach ($buyReportDailyPc as $value) {
			$lastTime = $value->settled_time;
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$criteria->addCondition("report_type = 1");
			$model = BuyReportDailyPc::model()->find($criteria);
			if($model === null){
				$model = new BuyReportDailyPc();
				$type = "creat";
			}
			
			$model = $this->mappingBuyReportDailyPc($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyPc/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyPc/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncBuyReportDailyPc",time());

		if(!empty($lastTime))
			$this->saveLog("lastSyncBuyReportDailyPcTosTime",$lastTime);
	}

	public function mappingBuyReportDailyPc($model,$tosPcBReport){

		$model->tos_id = $tosPcBReport->id;
		$model->report_type = 1;
		$model->settled_time = strtotime($tosPcBReport->settled_time);
		$model->campaign_id = $tosPcBReport->campaign_id;
		$model->ad_space_id = $tosPcBReport->ad_space_id;
		$model->strategy_id = $tosPcBReport->strategy_id;
		$model->creative_id = $tosPcBReport->creative_id;
		$model->media_category_id = $tosPcBReport->media_category_id;
		$model->screen_pos = $tosPcBReport->screen_pos;
		$model->adformat = $tosPcBReport->adformat;
		$model->width_height = $tosPcBReport->width_height;
		$model->pv = $tosPcBReport->pv;
		$model->impression = $tosPcBReport->impression;
		$model->impression_ten_sec = $tosPcBReport->impression_ten_sec;
		$model->click = $tosPcBReport->click;

		if(in_array($tosPcBReport->campaign_id, $this->noPayCampaign)){
			$model->media_cost = 0;
			$model->media_tax_cost = 0;
			$model->media_ops_cost = 0;
			$model->income = 0;
			$model->income_ten_sec = 0;
			$model->agency_income = 0;
			$this->writeLog(
				"Save : id " . $tosPcBReport->id . "time " . $tosPcBReport->settled_time . "org_media_cost" . $tosPcBReport->media_cost . "org_income" . $tosPcBReport->income,
				"SyncBuyReportDailyMob/nopay",
				date("YmdH") . "log.log"
			);				
		}else{
			$model->media_cost = $tosPcBReport->media_cost;
			$model->media_tax_cost = $tosPcBReport->media_tax_cost;
			$model->media_ops_cost = $tosPcBReport->media_ops_cost;
			$model->income = $tosPcBReport->income;
			$model->income_ten_sec = $tosPcBReport->income_ten_sec;
			$model->agency_income = $tosPcBReport->agency_income;
		}



		$model->is_outside_tracking = $tosPcBReport->is_outside_tracking;
		$model->sync_time = time();
		
		return $model;
	}	

	public function actionClearReportNoPay()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$criteria = new CDbCriteria;
		$criteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = TosCoreCampaign::model()->findAll($criteria);
		$this->noPayCampaign = array();
		foreach ($noPayCampaign as $value) {
			$this->noPayCampaign[] = $value->id;
		}	
		
		$criteria = new CDbCriteria;
		$criteria->addInCondition("campaign_id",$this->noPayCampaign);
		// $report = TosTreporBuyDrDisplayDailyMobReport::model()->findAll($criteria);
		BuyReportDailyPc::model()->updateAll(
			array(
				"media_cost" => 0,
				"media_tax_cost" => 0,
				"media_ops_cost" => 0,
				"income" => 0,
				"income_ten_sec" => 0,
				"agency_income" => 0,
			),
			$criteria
		);
	}

	public function actionSyncBuyReportDailyMobByDay()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$lastTimeLog = Log::model()->getValByName("lastSyncBuyReportDailyMobTosTime");

		$criteria = new CDbCriteria;
		$criteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = TosCoreCampaign::model()->findAll($criteria);
		$this->noPayCampaign = array();
		foreach ($noPayCampaign as $value) {
			$this->noPayCampaign[] = $value->id;
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("settled_time = '" . $_GET['day'] . "'");
		$buyReportDailyMob = TosTreporBuyDrDisplayDailyMobReport::model()->findAll($criteria);
		foreach ($buyReportDailyMob as $value) {
			$lastTime = $value->settled_time;
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$criteria->addCondition("report_type = 2");

			$model = BuyReportDailyPc::model()->find($criteria);
			if($model === null){
				$model = new BuyReportDailyPc();
				$type = "creat";
			}
			
			$model = $this->mappingBuyReportDailyMob($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyMob/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyMob/run",
					date("YmdH") . "log.log"
				);
			}
		}
	}


	public function actionSyncBuyReportDailyMob()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$lastTimeLog = Log::model()->getValByName("lastSyncBuyReportDailyMobTosTime");

		$criteria = new CDbCriteria;
		$criteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = TosCoreCampaign::model()->findAll($criteria);
		$this->noPayCampaign = array();
		foreach ($noPayCampaign as $value) {
			$this->noPayCampaign[] = $value->id;
		}

		$criteria = new CDbCriteria;
		$criteria->addCondition("settled_time > '" . $lastTimeLog . "'");
		$buyReportDailyMob = TosTreporBuyDrDisplayDailyMobReport::model()->findAll($criteria);
		foreach ($buyReportDailyMob as $value) {
			$lastTime = $value->settled_time;
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$criteria->addCondition("report_type = 2");

			$model = BuyReportDailyPc::model()->find($criteria);
			if($model === null){
				$model = new BuyReportDailyPc();
				$type = "creat";
			}
			
			$model = $this->mappingBuyReportDailyMob($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyMob/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncBuyReportDailyMob/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncBuyReportDailyMob",time());

		if(!empty($lastTime))
			$this->saveLog("lastSyncBuyReportDailyMobTosTime",$lastTime);
	}

	public function mappingBuyReportDailyMob($model,$tosPcBReport){

		$model->tos_id = $tosPcBReport->id;
		$model->report_type = 2;
		$model->settled_time = strtotime($tosPcBReport->settled_time);
		$model->campaign_id = $tosPcBReport->campaign_id;
		$model->ad_space_id = $tosPcBReport->ad_space_id;
		$model->strategy_id = $tosPcBReport->strategy_id;
		$model->creative_id = $tosPcBReport->creative_id;
		$model->media_category_id = $tosPcBReport->media_category_id;
		// $model->screen_pos = $tosPcBReport->screen_pos;
		$model->adformat = $tosPcBReport->adformat;
		$model->width_height = $tosPcBReport->width_height;
		$model->pv = $tosPcBReport->pv;
		$model->impression = $tosPcBReport->impression;
		$model->impression_ten_sec = $tosPcBReport->impression_ten_sec;
		$model->click = $tosPcBReport->click;

		if(in_array($tosPcBReport->campaign_id, $this->noPayCampaign)){
			$model->media_cost = 0;
			$model->media_tax_cost = 0;
			$model->media_ops_cost = 0;
			$model->income = 0;
			$model->income_ten_sec = 0;
			$model->agency_income = 0;
			$this->writeLog(
				"Save : id " . $tosPcBReport->id . "time " . $tosPcBReport->settled_time . "org_media_cost" . $tosPcBReport->media_cost . "org_income" . $tosPcBReport->income,
				"SyncBuyReportDailyMob/nopay",
				date("YmdH") . "log.log"
			);		
		}else{
			$model->media_cost = $tosPcBReport->media_cost;
			$model->media_tax_cost = $tosPcBReport->media_tax_cost;
			$model->media_ops_cost = $tosPcBReport->media_ops_cost;
			$model->income = $tosPcBReport->income;
			$model->income_ten_sec = $tosPcBReport->income_ten_sec;
			$model->agency_income = $tosPcBReport->agency_income;
		}


		$model->is_outside_tracking = $tosPcBReport->is_outside_tracking;
		$model->sync_time = time();

		return $model;
	}

	public function actionSyncSiteMediaCategory()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		// $lastTimeLog = Log::model()->getValByName("lastSyncSiteMediaCategoryTosTime");

		// $criteria = new CDbCriteria;
		// $criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$SyncSiteMediaCategory = TosCoreSiteMediaCategory::model()->findAll($criteria);
		foreach ($SyncSiteMediaCategory as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("site_id = " . $value->site_id);
			$model = SiteMediaCategory::model()->find($criteria);
			if($model === null){
				$model = new SiteMediaCategory();
				$type = "creat";
			}
			
			$model = $this->mappingSiteMediaCategory($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncSiteMediaCategory/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncSiteMediaCategory/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncSiteMediaCategory",time());

	}

	public function mappingSiteMediaCategory($model,$tosSiteMediaCategory){

		$model->site_id = $tosSiteMediaCategory->site_id;
		$model->category_id = $tosSiteMediaCategory->category_id;
		$model->status = $tosSiteMediaCategory->status;

		return $model;
	}

	// public function saveLog($name,$value){
	// 	$criteria = new CDbCriteria;
	// 	$criteria->addCondition("name = '" . $name . "'");
	// 	$model = Log::model()->find($criteria);
	// 	if($model === null)
	// 		$model = new Log();
	// 	$model->name = $name;
	// 	$model->value = $value;
	// 	$model->save();
	// }	

	public function actionSyncOldSupplier()
	{
		set_time_limit(0);
		$comType = array(
			"1" => 3, //國司
			"2" => 1, //國人
			"11" => 4, //外司
			"12" => 2, //外人
		);
		$zoneMapping = CfZoneMapping::model()->with("adSpace", "cfOldData", "adSpace.site", "adSpace.site.supplier")->findAll();
		// print_r($zoneMapping); exit;
		foreach ($zoneMapping as $value) {
			$Supplier = Supplier::model()->findByPk($value->adSpace->site->supplier->id);
			if($Supplier !== null){
				echo $Supplier->id;
				//$Supplier->name = ;
				//$Supplier->contacts = ;
				$Supplier->contacts_email = $value->cfOldData->cwEmail;
				$Supplier->contacts_tel = $value->cfOldData->cwOPhone;
				$Supplier->contacts_moblie = $value->cfOldData->cwMPhone;
				$Supplier->contacts_fax = $value->cfOldData->cwFax;
				$Supplier->fax = $value->cfOldData->comFax;
				$Supplier->tel = $value->cfOldData->comTel;
				$Supplier->email = $value->cfOldData->comEmail;
				//$Supplier->mobile = $value->cfOldData->comTel;
				//$Supplier->company_name  = $value->cfOldData->cwFax;
				//$Supplier->company_address = $value->cfOldData->cwFax;
				$Supplier->mail_address = $value->cfOldData->comMailAddr;
				$Supplier->invoice_name = $value->cfOldData->comInvName;
				$Supplier->tax_id = $value->cfOldData->comTaxID;

				$Supplier->type = $comType[$value->cfOldData->comType];

				$Supplier->country_code = $value->cfOldData->countryCode;
				$Supplier->account_name = $value->cfOldData->finAccountName;
				$Supplier->account_number = $value->cfOldData->finAccountNo;

				$Supplier->bank_name = $value->cfOldData->finBankName;

				$Supplier->bank_id = ((int)$value->cfOldData->finBankID > 0) ? $value->cfOldData->finBankID : "";

				$Supplier->bank_sub_name = $value->cfOldData->finSubBankName;

				$Supplier->bank_sub_id = ((int)$value->cfOldData->finSubBankID > 0) ? $value->cfOldData->finSubBankID : "";


				$Supplier->bank_type = ((int)$value->cfOldData->finBankID > 0) ? 1 : 0;
				$Supplier->bank_swift = ((int)$value->cfOldData->finBankID > 0) ? "" : $value->cfOldData->finBranchBankID;


				//$Supplier->remark = $value->cfOldData->countryCode;
				$Supplier->sync_time = time();
				$Supplier->save();
			}
		}
		$this->saveLog("lastSyncOldSupplier",time());
	}

	public function actionSyncOldSupplierByCompanyName()
	{
		set_time_limit(0);
		$comType = array(
			"1" => 3, //國司
			"2" => 1, //國人
			"11" => 4, //外司
			"12" => 2, //外人
		);
		$criteria = new CDbCriteria;
		$criteria->addCondition("type = 0");		
		$Suppliers = Supplier::model()->findAll($criteria);	

		foreach ($Suppliers as $value) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("comName LIKE '" . $value->name . "'","OR");
			$criteria->addCondition("comName LIKE '" . $value->company_name . "'","OR");	

			$cfOldData = MfOldData::model()->find($criteria);
			if($cfOldData !== null){
				print_r($Suppliers); //exit;
			}else{
				print_r($criteria); //exit;
			}

			if($cfOldData !== null){
				$Supplier = Supplier::model()->findByPk($value->id);
				// echo $Supplier->id;
				//$Supplier->name = ;
				//$Supplier->contacts = ;
				$Supplier->contacts_email = $cfOldData->cwEmail;
				$Supplier->contacts_tel = $cfOldData->cwOPhone;
				$Supplier->contacts_moblie = $cfOldData->cwMPhone;
				$Supplier->contacts_fax = $cfOldData->cwFax;
				$Supplier->fax = $cfOldData->comFax;
				$Supplier->tel = $cfOldData->comTel;
				$Supplier->email = $cfOldData->comEmail;
				//$Supplier->mobile = $cfOldData->comTel;
				//$Supplier->company_name  = $cfOldData->cwFax;
				//$Supplier->company_address = $cfOldData->cwFax;
				$Supplier->mail_address = $cfOldData->comMailAddr;
				$Supplier->invoice_name = $cfOldData->comInvName;
				$Supplier->tax_id = $cfOldData->comTaxID;

				$Supplier->type = $comType[$cfOldData->comType];

				$Supplier->country_code = $cfOldData->countryCode;
				$Supplier->account_name = $cfOldData->finAccountName;
				$Supplier->account_number = $cfOldData->finAccountNo;

				$Supplier->bank_name = $cfOldData->finBankName;

				$Supplier->bank_id = ((int)$cfOldData->finBankID > 0) ? $cfOldData->finBankID : "";

				$Supplier->bank_sub_name = $cfOldData->finSubBankName;

				$Supplier->bank_sub_id = ((int)$cfOldData->finSubBankID > 0) ? $cfOldData->finSubBankID : "";


				$Supplier->bank_type = ((int)$cfOldData->finBankID > 0) ? 1 : 0;
				$Supplier->bank_swift = ((int)$cfOldData->finBankID > 0) ? "" : $cfOldData->finBranchBankID;


				//$Supplier->remark = $value->cfOldData->countryCode;
				$Supplier->sync_time = time();
				$Supplier->save();
			}
		}
		$this->saveLog("lastSyncOldSupplier",time());
	}

	public function actionSyncAdvertisers()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		// $lastTimeLog = Log::model()->getValByName("lastSyncAdvertisers");

		// $criteria = new CDbCriteria;
		// $criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$tosCoreAdvertisers = TosCoreAdvertisers::model()->findAll($criteria);
		foreach ($tosCoreAdvertisers as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = Advertisers::model()->find($criteria);
			if($model === null){
				$model = new Advertisers();
				$type = "creat";
			}
			
			$model = $this->mappingAdvertisers($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncAdvertisers/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncAdvertisers/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncAdvertisers",time());
	}

	public function mappingAdvertisers($model,$tosCoreAdvertisers){
		$model->tos_id = $tosCoreAdvertisers->id;
		$model->advertiser_name = $tosCoreAdvertisers->advertiser_name;
		$model->short_name = $tosCoreAdvertisers->short_name;
		$model->site_url = $tosCoreAdvertisers->site_url;
		$model->industry_id = $tosCoreAdvertisers->industry_id;
		$model->account_id = $tosCoreAdvertisers->account_id;
		$model->default_minisite_id = $tosCoreAdvertisers->default_minisite_id;
		$model->category = $tosCoreAdvertisers->category;
		$model->remark = $tosCoreAdvertisers->remark;
		$model->organization_code = $tosCoreAdvertisers->organization_code;
		$model->source = $tosCoreAdvertisers->source;
		$model->audit_status = $tosCoreAdvertisers->audit_status;
		$model->status = $tosCoreAdvertisers->status;
		$model->adv_rate = $tosCoreAdvertisers->adv_rate;
		$model->submit_status = $tosCoreAdvertisers->submit_status;
		$model->pre_status = $tosCoreAdvertisers->pre_status;
		$model->standard_id = $tosCoreAdvertisers->standard_id;
		$model->advertiser_type = $tosCoreAdvertisers->advertiser_type;
		$model->sync_time = time();

		return $model;
	}

	public function actionSyncCampaign()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		// $lastTimeLog = Log::model()->getValByName("lastSyncCampaign");

		// $criteria = new CDbCriteria;
		// $criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$tosCoreCampaign = TosCoreCampaign::model()->findAll($criteria);
		foreach ($tosCoreCampaign as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = Campaign::model()->find($criteria);
			if($model === null){
				$model = new Campaign();
				$type = "creat";
			}
			
			$model = $this->mappingCampaign($model,$value);
			if(!$model->save()){
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCampaign/error",
					date("Ymd") . "errorLog.log"
				);
			}else{
				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCampaign/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncCampaign",time());
	}

	public function mappingCampaign($model,$tosCoreCampaign){
		$model->tos_id = $tosCoreCampaign->id;
		$model->campaign_name =  $tosCoreCampaign->campaign_name;
		$model->advertiser_id =  $tosCoreCampaign->advertiser_id;
		$model->account_id =  $tosCoreCampaign->account_id;
		$model->industry_id =  $tosCoreCampaign->industry_id;
		$model->site_id =  $tosCoreCampaign->site_id;
		$model->start_time =  strtotime($tosCoreCampaign->start_time);
		$model->end_time =  strtotime($tosCoreCampaign->end_time);
		$model->remark =  $tosCoreCampaign->remark;
		$model->adv_feature =  $tosCoreCampaign->adv_feature;
		$model->source =  $tosCoreCampaign->source;
		$model->status =  $tosCoreCampaign->status;
		$model->adv_rate =  $tosCoreCampaign->adv_rate;
		$model->brand_id =  $tosCoreCampaign->brand_id;
		$model->product_id =  $tosCoreCampaign->product_id;
		$model->brief_id =  $tosCoreCampaign->brief_id;
		$model->create_user =  $tosCoreCampaign->create_user;
		$model->close_price =  0;
		$model->sync_time = time();

		return $model;
	}

	public function actionSyncCampaignBudget()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		// $lastTimeLog = Log::model()->getValByName("lastSyncCampaignBudget");

		// $criteria = new CDbCriteria;
		// $criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$tosCoreCampaignBudget = TosCoreCampaignBudget::model()->findAll($criteria);

		foreach ($tosCoreCampaignBudget as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = CampaignBudget::model()->find($criteria);
			if($model === null){
				$model = new CampaignBudget();
				$type = "creat";
			}
			
			$model = $this->mappingCampaignBudget($model,$value);
			if(!$model->save()){

				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCampaignBudget/error",
					date("Ymd") . "errorLog.log"
				);
			}else{

				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCampaignBudget/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncCampaignBudget",time());
	}

	public function mappingCampaignBudget($model,$tosCoreCampaignBudget){
		$model->tos_id = $tosCoreCampaignBudget->id;
		$model->campaign_id = $tosCoreCampaignBudget->campaign_id;
		$model->total_budget = $tosCoreCampaignBudget->total_budget;
		$model->max_daily_budget = $tosCoreCampaignBudget->max_daily_budget;
		$model->total_pv = $tosCoreCampaignBudget->total_pv;
		$model->max_daily_pv = $tosCoreCampaignBudget->max_daily_pv;
		$model->total_click = $tosCoreCampaignBudget->total_click;
		$model->max_daily_click = $tosCoreCampaignBudget->max_daily_click;
		$model->total_viewable = $tosCoreCampaignBudget->total_viewable;
		$model->max_daily_viewable = $tosCoreCampaignBudget->max_daily_viewable;
		$model->status = $tosCoreCampaignBudget->status;
		$model->sync_time = time();

		return $model;
	}

	public function actionSyncStrategy()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		// $lastTimeLog = Log::model()->getValByName("lastSyncStrategy");

		// $criteria = new CDbCriteria;
		// $criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$tosCoreStrategy = TosCoreStrategy::model()->findAll($criteria);

		foreach ($tosCoreStrategy as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = Strategy::model()->find($criteria);
			if($model === null){
				$model = new Strategy();
				$type = "creat";
			}
			
			$model = $this->mappingStrategy($model,$value);
			if(!$model->save()){
				
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncStrategy/error",
					date("Ymd") . "errorLog.log"
				);
			}else{

				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncStrategy/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncStrategy",time());
	}

	public function mappingStrategy($model,$tosCoreStrategy){
		$model->tos_id = $tosCoreStrategy->id;
		$model->strategy_name = $tosCoreStrategy->strategy_name;
		$model->strategy_type = $tosCoreStrategy->strategy_type;
		$model->medium = $tosCoreStrategy->medium;
		$model->campaign_id = $tosCoreStrategy->campaign_id;
		$model->tags = $tosCoreStrategy->tags;
		$model->buy_mode = $tosCoreStrategy->buy_mode;
		$model->bidding_type = $tosCoreStrategy->bidding_type;
		$model->bidding_price = $tosCoreStrategy->bidding_price;
		$model->kpi_type = $tosCoreStrategy->kpi_type;
		$model->kpi_value = $tosCoreStrategy->kpi_value;
		$model->sec_kpi_type = $tosCoreStrategy->sec_kpi_type;
		$model->sec_kpi_value = $tosCoreStrategy->sec_kpi_value;
		$model->charge_type = $tosCoreStrategy->charge_type;
		$model->priority = $tosCoreStrategy->priority;
		$model->weight = $tosCoreStrategy->weight;
		$model->pacing_type = $tosCoreStrategy->pacing_type;
		$model->charge_price = $tosCoreStrategy->charge_price;
		$model->imp_tracking = $tosCoreStrategy->imp_tracking;
		$model->status = $tosCoreStrategy->status;
		$model->creative_tag = $tosCoreStrategy->creative_tag;
		$model->start_time = strtotime($tosCoreStrategy->start_time);
		$model->end_time = strtotime($tosCoreStrategy->end_time);
		$model->adv_feature = $tosCoreStrategy->adv_feature;
		$model->ops_rate = $tosCoreStrategy->ops_rate;
		$model->account_id = $tosCoreStrategy->account_id;
		$model->range_type = $tosCoreStrategy->range_type;
		$model->range_price = $tosCoreStrategy->range_price;
		$model->bidding_strategy = $tosCoreStrategy->bidding_strategy;
		$model->sync_time = time();

		return $model;
	}

	public function actionSyncCreativeMaterial()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		// $lastTimeLog = Log::model()->getValByName("lastSyncCreativeMaterial");

		// $criteria = new CDbCriteria;
		// $criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$tosCoreCreativeMaterial = TosCoreCreativeMaterial::model()->findAll($criteria);

		foreach ($tosCoreCreativeMaterial as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = CreativeMaterial::model()->find($criteria);
			if($model === null){
				$model = new CreativeMaterial();
				$type = "creat";
			}
			
			$model = $this->mappingCreativeMaterial($model,$value);
			if(!$model->save()){
				
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCreativeMaterial/error",
					date("Ymd") . "errorLog.log"
				);
			}else{

				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCreativeMaterial/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncCreativeMaterial",time());
	}

	public function mappingCreativeMaterial($model,$tosCoreCreativeMaterial){
		$model->tos_id = $tosCoreCreativeMaterial->id;
		$model->name = $tosCoreCreativeMaterial->name;
		$model->creative_group_id = $tosCoreCreativeMaterial->creative_group_id;
		$model->campaign_id = $tosCoreCreativeMaterial->campaign_id;
		$model->account_id = $tosCoreCreativeMaterial->account_id;
		$model->adv_feature = $tosCoreCreativeMaterial->adv_feature;
		$model->size_id = $tosCoreCreativeMaterial->size_id;
		$model->width = $tosCoreCreativeMaterial->width;
		$model->height = $tosCoreCreativeMaterial->height;
		$model->material_format = $tosCoreCreativeMaterial->material_format;
		$model->play_time = $tosCoreCreativeMaterial->play_time;
		$model->status = $tosCoreCreativeMaterial->status;
		$model->category = $tosCoreCreativeMaterial->category;
		$model->material_url = $tosCoreCreativeMaterial->material_url;
		$model->material_content = $tosCoreCreativeMaterial->material_content;
		$model->material_type = $tosCoreCreativeMaterial->material_type;
		$model->size = $tosCoreCreativeMaterial->size;
		$model->is_mraid = $tosCoreCreativeMaterial->is_mraid;
		$model->sync_time = time();

		return $model;
	}

	public function actionSyncCreativeGroups()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		// $lastTimeLog = Log::model()->getValByName("lastSyncCreativeGroups");

		// $criteria = new CDbCriteria;
		// $criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$tosCoreCreativeGroups = TosCoreCreativeGroups::model()->findAll($criteria);

		foreach ($tosCoreCreativeGroups as $value) {
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("tos_id = " . $value->id);
			$model = CreativeGroups::model()->find($criteria);
			if($model === null){
				$model = new CreativeGroups();
				$type = "creat";
			}
			
			$model = $this->mappingCreativeGroups($model,$value);
			if(!$model->save()){
				
				$this->writeLog(
					"儲存同步資料時發生錯誤 : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCreativeGroups/error",
					date("Ymd") . "errorLog.log"
				);
			}else{

				$this->writeLog(
					"Save : SID=" . $model->id . ",TYPE=" . $type . ",TID=" . $value->id,
					"SyncCreativeGroups/run",
					date("YmdH") . "log.log"
				);
			}
		}
		$this->saveLog("lastSyncCreativeGroups",time());
	}

	public function mappingCreativeGroups($model,$tosCoreCreativeGroups){
		$model->tos_id = $tosCoreCreativeGroups->id;
		$model->name = $tosCoreCreativeGroups->name;
		$model->medium = $tosCoreCreativeGroups->medium;
		$model->campaign_id = $tosCoreCreativeGroups->campaign_id;
		$model->account_id = $tosCoreCreativeGroups->account_id;
		$model->ad_format = $tosCoreCreativeGroups->ad_format;
		$model->targeting_url = $tosCoreCreativeGroups->targeting_url;
		$model->click_tracking = $tosCoreCreativeGroups->click_tracking;
		$model->content_type_id = $tosCoreCreativeGroups->content_type_id;
		$model->template_id = $tosCoreCreativeGroups->template_id;
		$model->adv_feature = $tosCoreCreativeGroups->adv_feature;
		$model->status = $tosCoreCreativeGroups->status;
		$model->material_delivery = $tosCoreCreativeGroups->material_delivery;
		$model->creative_concept_id = $tosCoreCreativeGroups->creative_concept_id;
		$model->is_default = $tosCoreCreativeGroups->is_default;
		$model->source = $tosCoreCreativeGroups->source;
		$model->channel_type = $tosCoreCreativeGroups->channel_type;
		$model->media_terminal = $tosCoreCreativeGroups->media_terminal;
		$model->sync_time = time();

		return $model;
	}

}