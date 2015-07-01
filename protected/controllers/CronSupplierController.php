<?php

class CronSupplierController extends Controller
{

	//累計本月可請款金額，可重複計算於每個月１日啟動，如不累計需先清除
	public function actionCronUnapplicationMonies()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		$pcSite = $this->getPcSite();

		foreach ($pcSite as $site) {
			foreach ($site->adSpace as $space) {
				$pcReport = $this->getPcSpaceMonies($space->tos_id);

				//print_r($pcReport); exit;

				if(!empty($pcReport))
					$this->countPcSupplierMonies($pcReport);
			}
		}
		
		$this->saveLog("lastCronUnapplicationMonies",time());
	}

	public function getPcSite(){
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.type = 1");
		$criteria->addCondition("adSpace.tos_id IS NOT NULL");
		return Site::model()->with("adSpace")->findAll($criteria);
	}

	public function getPcSpaceMonies($adSpaceId){

		$criteria = new CDbCriteria;
		$criteria->addCondition("adSpace_id = " . $adSpaceId);
		$lastTime = AdSpaceCronCountLog::model()->find($criteria);
		$lastTime = ($lastTime === null)? 0 : $lastTime->last_count_time;

		//print_r($lastTime);exit;

		if($_GET['test'] == 1)
			$lastTime = 0;

		//print_r($lastCronUnapplicationMonies); exit;
		$criteria = new CDbCriteria;
		//$criteria->addCondition("sync_time > " . strtotime(date("Y-m") . "-01 00:00:00"));
		$criteria->addCondition("sync_time > " . $lastTime);
		// $criteria->addCondition("settled_time > 1434729600");
		$criteria->addCondition("ad_space_id = " . $adSpaceId);
		// $criteria->addInCondition("ad_space_id", $adSpaceId);
		$criteria->select = "sum(media_cost)/100000 AS media_cost_count, sum(pv) AS pv, sum(click) AS click, ad_space_id";
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

		//$supplierApplicationMonies->this_application = strtotime(date("Y-m")."-01");
		$supplierApplicationMonies->supplier_id = $space->site->supplier->tos_id;
		$supplierApplicationMonies->site_id = $space->site->tos_id;
		$supplierApplicationMonies->adSpace_id =  $space->tos_id;
		$beforSaveMonies = $supplierApplicationMonies->month_monies;
		$supplierApplicationMonies->month_monies += $model->media_cost_count;
		$supplierApplicationMonies->this_application = $monthOfAccount->value;
		$supplierApplicationMonies->update_time = time();
		if(!$supplierApplicationMonies->save()){

			//print_r($supplierApplicationMonies->getErrors()); exit;
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
				$supplierMoniesMonthly->imp = $model->pv;
				$supplierMoniesMonthly->click = $model->click;				
			}else{
				$supplierMoniesMonthly->imp += $model->pv;
				$supplierMoniesMonthly->click += $model->click;					
			}

			$supplierMoniesMonthly->supplier_id = $space->site->supplier->tos_id;
			$supplierMoniesMonthly->site_id = $space->site->tos_id;
			$supplierMoniesMonthly->adSpace_id = $space->tos_id;
			$supplierMoniesMonthly->total_monies = $supplierApplicationMonies->month_monies;
			$supplierMoniesMonthly->year = date("Y",$monthOfAccount->value);
			$supplierMoniesMonthly->month = date("m",$monthOfAccount->value);
			$supplierMoniesMonthly->buy_type = $space->buy_type;
			$supplierMoniesMonthly->charge_type = $space->charge_type;
			$supplierMoniesMonthly->price = $space->price;

			if(!$supplierMoniesMonthly->save()){
				print_r($supplierMoniesMonthly);
				print_r($supplierMoniesMonthly->getErrors()); exit;
			}

		}
	}
}