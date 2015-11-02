<?php

class CronApplicationMoniesController extends Controller
{

	public function actionCronApplication()
	{
		set_time_limit(0);
		ini_set("memory_limit","2048M");

		//echo strtotime(date("Y-" . date("m",strtotime("-1 Months",strtotime("2015-07-01"))) . "-01")); exit;

		$this->closeAccountsStatus();


		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");
		//print_r($month_of_account); exit;
		$monthOfAccount->value = strtotime(date("Y-m-01",strtotime("-1 Months",time())));
		$monthOfAccount->save();	

		//清除已申請完成的請款
		$this->clearSupplierMonies();

		//清除系統退回未申請的請款
		$this->clearApplication();

		//累計本月額度
		$this->transSupplierMonies();

		$this->saveLog("lastCronApplication",time());
	}

	public function closeAccountsStatus(){
		$accountsStatus = SiteSetting::model()->getValByKey("accounts_status");
		$accountsStatus->value = 0;
		$accountsStatus->save();			
	}	

	//清除已申請完成的請款
	public function clearSupplierMonies(){
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");
		$criteria = new CDbCriteria;
		$criteria->addCondition("status = 3");
		$criteria->addCondition("year = '" . date("Y", strtotime("-1 Months",$monthOfAccount->value)) . "'");
		$criteria->addCondition("month = '" . date("m", strtotime("-1 Months",$monthOfAccount->value)) . "'");
		$application = SupplierApplicationLog::model()->findAll($criteria);

		// print_r($criteria); exit;
		foreach ($application as $value) {
			SupplierApplicationMonies::model()->updateAll(
				array(
					'total_monies' => 0,
					'month_monies' => 0,
					'last_application' => $monthOfAccount->value
				),
				'supplier_id = ' . $value->supplier_id
			);
		}		
		
	}

	public function clearApplication(){
		SupplierApplicationLog::model()->updateAll(
			array(
				'status' => 0,
				'lock' => 0
			),
			'status != 3'
		);		
	}

	public function transSupplierMonies(){
		$model = SupplierApplicationMonies::model()->findAll();
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");
		foreach ($model as $value) {
			$thisModel = SupplierApplicationMonies::model()->findByPk($value->id);
			$thisModel->total_monies = $thisModel->total_monies + $thisModel->month_monies;
			$thisModel->application_type = 0;
			$thisModel->application_id = 0;
			$thisModel->application_by = 0;
			$thisModel->month_monies = 0;
			$thisModel->this_application = $monthOfAccount->value;
			//print_r($thisModel->this_application); exit;
			$thisModel->save();
		}
	}

}