<?php

class CronSupplierController extends Controller
{

	//累計本月可請款金額，可重複計算於每個月１日啟動，如不累計需先清除
	public function actionCronUnapplicationMonies()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$pcSite = $this->getPcSite();
		$adSpaceArray = array();

		$noPayCriteria = new CDbCriteria;
		$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
		$noPayCampaignId = array();
		foreach ($noPayCampaign as $value) {
			$noPayCampaignId[] = $value->tos_id;
		}

		foreach ($pcSite as $site) {
			foreach ($site->adSpace as $space) {
				$pcReport = $this->getPcSpaceMonies($space->tos_id,$noPayCampaignId);

				if(!empty($pcReport)){
					$this->countPcSupplierMonies($pcReport);
					$adSpaceArray[] = $space->tos_id;
				}
			}
		}
		
		$this->syncSupplierMoniesMonthlyByZero($adSpaceArray);
		$this->saveLog("lastCronUnapplicationMonies",time());
	}

	public function syncSupplierMoniesMonthlyByZero($adSpaceArray){
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");
		$criteria = new CDbCriteria;
		$criteria->addCondition("total_monies > 0");
		$criteria->addNotInCondition("adSpace_id",$adSpaceArray);
		$adSpace = SupplierApplicationMonies::model()->with("adSpace")->findAll($criteria);
		foreach ($adSpace as $value) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("adSpace_id = '" . $value->adSpace_id . "'");
			$criteria->addCondition("year = '" . date("Y",$monthOfAccount->value) . "'");
			$criteria->addCondition("month = '" . date("m",$monthOfAccount->value) . "'");
			$supplierMoniesMonthly = SupplierMoniesMonthly::model()->find($criteria);
			if($supplierMoniesMonthly === null){
				$supplierMoniesMonthly = new SupplierMoniesMonthly();
			}
			$supplierMoniesMonthly->total_monies = $value->total_monies;	
			$supplierMoniesMonthly->imp = 0;
			$supplierMoniesMonthly->click = 0;	
			$supplierMoniesMonthly->supplier_id = $value->supplier_id;
			$supplierMoniesMonthly->site_id = $value->site_id;
			$supplierMoniesMonthly->adSpace_id = $value->adSpace_id;
			$supplierMoniesMonthly->year = date("Y",$monthOfAccount->value);
			$supplierMoniesMonthly->month = date("m",$monthOfAccount->value);
			$supplierMoniesMonthly->buy_type = $value->adSpace->buy_type;
			$supplierMoniesMonthly->charge_type = $value->adSpace->charge_type;
			$supplierMoniesMonthly->price = $value->adSpace->price;

			if(!$supplierMoniesMonthly->save()){
				print_r($supplierMoniesMonthly);
				print_r($supplierMoniesMonthly->getErrors()); exit;
			}
		}
	}

	public function getPcSite(){
		$criteria = new CDbCriteria;
		// $criteria->addCondition("t.type = 1");
		$criteria->addCondition("adSpace.tos_id IS NOT NULL");
		return Site::model()->with("adSpace")->findAll($criteria);
	}

	public function getPcSpaceMonies($adSpaceId,$noPayCampaignId){

		$criteria = new CDbCriteria;
		$criteria->addCondition("adSpace_id = " . $adSpaceId);
		$lastTime = AdSpaceCronCountLog::model()->find($criteria);
		$lastTime = ($lastTime === null)? 0 : $lastTime->last_count_time;

		//print_r($lastTime);exit;

		if($_GET['test'] == 1)
			$lastTime = 0;

		//print_r($lastCronUnapplicationMonies); exit;
		$criteria = new CDbCriteria;
		// $criteria->addCondition("sync_time > " . strtotime(date("Y-m") . "-01 00:00:00"));
		// $criteria->addCondition("settled_time >= " . strtotime("2015-08-01 00:00:00"));
		// $criteria->addCondition("settled_time <= " . strtotime("2015-08-31 00:00:00"));
		$criteria->addCondition("sync_time > " . $lastTime);
		// $criteria->addCondition("settled_time > 1434729600");
		$criteria->addCondition("ad_space_id = " . $adSpaceId);
		// $criteria->addInCondition("ad_space_id", $adSpaceId);
		$criteria->select = "sum(media_cost)/100000 AS media_cost_count, sum(impression) AS impression, sum(click) AS click, ad_space_id";
		$criteria->addNotInCondition("campaign_id",$noPayCampaignId);

		$criteria->group = 'ad_space_id';
		return BuyReportDailyPc::model()->find($criteria);
	}

	public function countPcSupplierMonies($model){
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.tos_id = '" . $model->ad_space_id . "'");
		$space = AdSpace::model()->with("site","site.supplier")->find($criteria);

		$criteria = new CDbCriteria;
		$criteria->addCondition("adSpace_id = '" . $model->ad_space_id . "'");
		$type = "update";
		$supplierApplicationMonies = SupplierApplicationMonies::model()->find($criteria);

		//建立新資料
		if($supplierApplicationMonies === null){
			$type = "create";
			$supplierApplicationMonies = new SupplierApplicationMonies();
			$supplierApplicationMonies->total_monies = 0;
			$supplierApplicationMonies->last_application = $monthOfAccount->value;
			$supplierApplicationMonies->application_type = 0;
			$supplierApplicationMonies->application_id = 0;
			$supplierApplicationMonies->application_by = 0;	
			$supplierApplicationMonies->create_time = time();		
		}
		
		$supplierApplicationMonies->supplier_id = $space->site->supplier->tos_id;
		$supplierApplicationMonies->site_id = $space->site->tos_id;
		$supplierApplicationMonies->adSpace_id =  $space->tos_id;

		//紀錄更改前金額
		$beforSaveMonies = $supplierApplicationMonies->month_monies;


		$supplierApplicationMonies->month_monies += $model->media_cost_count;
		$supplierApplicationMonies->this_application = $monthOfAccount->value;
		$supplierApplicationMonies->update_time = time();



		if(!$supplierApplicationMonies->save()){
			$this->writeLog(
				"儲存同步資料時發生錯誤 : SID=" . $supplierApplicationMonies->id . ",TYPE=" . $type . ",adSpace_id=" . $model->ad_space_id,
				"CronSupplier/error",
				date("Ymd") . "errorLog.log"
			);
			$this->writeLog(
				"=================================================",
				"CronSupplier/error",
				date("Ymd") . "errorLog.log"
			);
			$this->writeLog(
				serialize($supplierApplicationMonies->getErrors()),
				"CronSupplier/error",
				date("Ymd") . "errorLog.log"
			);	
			$this->writeLog(
				"=================================================",
				"CronSupplier/error",
				date("Ymd") . "errorLog.log"
			);							
		}else{
			$this->writeLog(
				"Save : SID=" . $supplierApplicationMonies->id . ",TYPE=" . $type . ",adSpace_id=" . $model->ad_space_id ."BM=" . $beforSaveMonies ."NM=" . $supplierApplicationMonies->month_monies,
				"CronSupplier/run",
				date("YmdH") . "log.log"
			);

			$criteria = new CDbCriteria;
			$criteria->addCondition("adSpace_id = " . $model->ad_space_id);
			$lastTime = AdSpaceCronCountLog::model()->find($criteria);
			if($lastTime === null){
				$lastTime = new AdSpaceCronCountLog();
			}
			$lastTime->adSpace_id = $model->ad_space_id;
			$lastTime->last_count_time = time();
			$lastTime->save();


			//供應商月帳
			$criteria = new CDbCriteria;
			$criteria->addCondition("adSpace_id = '" . $space->tos_id . "'");
			$criteria->addCondition("year = '" . date("Y",$monthOfAccount->value) . "'");
			$criteria->addCondition("month = '" . date("m",$monthOfAccount->value) . "'");
			$supplierMoniesMonthly = SupplierMoniesMonthly::model()->find($criteria);
			if($supplierMoniesMonthly === null){
				$supplierMoniesMonthly = new SupplierMoniesMonthly();
				$supplierMoniesMonthly->total_monies = 0;				
			}

			$supplierMoniesMonthly->imp = $model->impression;
			$supplierMoniesMonthly->click = $model->click;					

			$supplierMoniesMonthly->supplier_id = $space->site->supplier->tos_id;
			$supplierMoniesMonthly->site_id = $space->site->tos_id;
			$supplierMoniesMonthly->adSpace_id = $space->tos_id;
			$supplierMoniesMonthly->total_monies = $supplierApplicationMonies->month_monies + $supplierApplicationMonies->total_monies;
			if($supplierApplicationMonies->total_monies > 0){
				$criteria = new CDbCriteria;
				$criteria->addCondition("adSpace_id = '" . $space->tos_id . "'");
				$criteria->order = "t.id DESC";
				$lastSupplierMoniesMonthly = SupplierMoniesMonthly::model()->find($criteria);				
				if($lastSupplierMoniesMonthly !== null){
					$supplierMoniesMonthly->imp += $lastSupplierMoniesMonthly->imp;
					$supplierMoniesMonthly->click += $lastSupplierMoniesMonthly->click;	
					if($space->tos_id == "100282585"){
						print_r($supplierMoniesMonthly); exit;
					}					
				}
			}
			$supplierMoniesMonthly->year = date("Y",$monthOfAccount->value);
			$supplierMoniesMonthly->month = date("m",$monthOfAccount->value);
			$supplierMoniesMonthly->buy_type = $space->buy_type;
			$supplierMoniesMonthly->charge_type = $space->charge_type;
			$supplierMoniesMonthly->price = $space->price;

			// if($space->tos_id == "100282585"){
			// 	print_r($supplierMoniesMonthly); exit;
			// }

			if(!$supplierMoniesMonthly->save()){
				// print_r($supplierMoniesMonthly);
				// print_r($supplierMoniesMonthly->getErrors()); exit;
			}

		}
	}

	public function ActionReCountPcSupplierMonies(){
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");
		$criteria = new CDbCriteria;
		$criteria->addCondition("year = '" . date("Y",$monthOfAccount->value) . "'");
		$criteria->addCondition("month = '" . date("m",$monthOfAccount->value) . "'");
		$supplierMoniesMonthly = SupplierMoniesMonthly::model()->findAll($criteria);
		foreach ($supplierMoniesMonthly as $value) {
			$criteria = new CDbCriteria;
			$criteria->addCondition("adSpace_id = '" . $value->adSpace_id . "'");
			$supplierApplicationMonies = SupplierApplicationMonies::model()->find($criteria);
			$monthly = SupplierMoniesMonthly::model()->findByPk($value->id);	
			$monthly->total_monies = $supplierApplicationMonies->month_monies + $supplierApplicationMonies->total_monies;
			$monthly->save();
		}
	}

	public function ActionYearsClear(){
		set_time_limit(0);
		//先執行清除
		$this->clearSupplierMonies();

		$criteria = new CDbCriteria;
		$criteria->addCondition("total_monies > 0 OR month_monies > 0");		
		$model = SupplierApplicationMonies::model()->findAll($criteria);
		foreach($model as $monies){
			$supplierYearAccounts = new SupplierYearAccounts();
			$supplierYearAccounts->supplier_id = $monies->supplier_id;
			$supplierYearAccounts->site_id = $monies->site_id;
			$supplierYearAccounts->adSpace_id = $monies->adSpace_id;
			$supplierYearAccounts->total_monies = $monies->total_monies + $monies->month_monies;
			$supplierYearAccounts->last_application = $monies->last_application;
			$supplierYearAccounts->this_application = $monies->this_application;
			//$supplierYearAccounts->year = date("Y",strtotime("-1 year"));
			$supplierYearAccounts->year = "2015";
			$supplierYearAccounts->application_type = 0;
			$supplierYearAccounts->application_id = 0;
			$supplierYearAccounts->application_by = 0;
			$supplierYearAccounts->create_time = time();
			$supplierYearAccounts->update_time = time();
			$supplierYearAccounts->save();
		}

		$this->clearAllSupplierMonies();
		
	}	

	
	public function clearSupplierMonies(){
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");
		$criteria = new CDbCriteria;
		$criteria->addCondition("status = 3");
		$criteria->addCondition("year = '" . date("Y", $monthOfAccount->value) . "'");
		$criteria->addCondition("month = '" . date("m", $monthOfAccount->value) . "'");
		$application = SupplierApplicationLog::model()->findAll($criteria);
		// print_r($application); exit;
		
		foreach ($application as $value) {
			SupplierApplicationMonies::model()->updateAll(
				array(
					'total_monies' => 0,
					'month_monies' => 0,
					// 'last_application' => $monthOfAccount->value
				),
				'supplier_id = ' . $value->supplier_id
			);
			print_r($value->supplier_id."<br>");
		}		
		
	}


	public function clearAllSupplierMonies(){		
		SupplierApplicationMonies::model()->updateAll(
			array(
				'total_monies' => 0,
				'month_monies' => 0,
				// 'last_application' => $monthOfAccount->value
			)
		);	
	}	

}