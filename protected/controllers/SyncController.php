<?php

class SyncController extends Controller
{

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

	public function actionSyncBuyReportDailyPc()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$lastTimeLog = Log::model()->getValByName("lastSyncBuyReportDailyPcTosTime");
		
		$criteria = new CDbCriteria;
		$criteria->addCondition("settled_time > '" . $lastTimeLog . "'");
		$buyReportDailyPc = TosTreporBuyDrDisplayDailyPcReport::model()->findAll($criteria);
		// print_r($buyReportDailyPc); exit;

		
		foreach ($buyReportDailyPc as $value) {
			$lastTime = $value->settled_time;
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $value->id);
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

		$model->id = $tosPcBReport->id;
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
		$model->media_cost = $tosPcBReport->media_cost;
		$model->media_tax_cost = $tosPcBReport->media_tax_cost;
		$model->media_ops_cost = $tosPcBReport->media_ops_cost;
		$model->income = $tosPcBReport->income;
		$model->income_ten_sec = $tosPcBReport->income_ten_sec;
		$model->agency_income = $tosPcBReport->agency_income;
		$model->is_outside_tracking = $tosPcBReport->is_outside_tracking;
		$model->sync_time = time();

		return $model;
	}	

	public function actionSyncBuyReportDailyMob()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");
		$lastTimeLog = Log::model()->getValByName("lastSyncBuyReportDailyMobTosTime");

		$criteria = new CDbCriteria;
		$criteria->addCondition("settled_time > '" . $lastTimeLog->value . "'");
		$buyReportDailyMob = TosTreporBuyDrDisplayDailyMobReport::model()->findAll($criteria);
		foreach ($buyReportDailyMob as $value) {
			$lastTime = $value->settled_time;
			$type = "update";
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . $value->id);
			$model = BuyReportDailyPc::model()->find($criteria);
			if($model === null){
				$model = new BuyReportDailyPc();
				$type = "creat";
			}
			
			$model = $this->mappingBuyReportDailyMob($model,$value);
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
		$this->saveLog("lastSyncBuyReportDailyMob",time());

		if(!empty($lastTime))
			$this->saveLog("lastSyncBuyReportDailyMobTosTime",$lastTime);
	}

	public function mappingBuyReportDailyMob($model,$tosPcBReport){

		$model->id = $tosPcBReport->id;
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
		$model->media_cost = $tosPcBReport->media_cost;
		$model->media_tax_cost = $tosPcBReport->media_tax_cost;
		$model->media_ops_cost = $tosPcBReport->media_ops_cost;
		$model->income = $tosPcBReport->income;
		$model->income_ten_sec = $tosPcBReport->income_ten_sec;
		$model->agency_income = $tosPcBReport->agency_income;
		$model->is_outside_tracking = $tosPcBReport->is_outside_tracking;
		$model->sync_time = time();

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

}