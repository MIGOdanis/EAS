<?php

class AccountsStatusController extends Controller
{
	public function actionAdmin()
	{
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_account");
		$accountsStatus = SiteSetting::model()->getValByKey("accounts_status");
		$accountUpdateTime = SiteSetting::model()->getValByKey("account_update_time");

		if(isset($_GET['status'])){
			$accountsStatus->value = ($accountsStatus->value == 1)? 0: 1;
			$accountsStatus->save();
			$accountUpdateTime->value = time();
			$accountUpdateTime->save();
			$this->writeLog(
				"執行開關帳 : UID = " . Yii::app()->user->id . ",TYPE=" . $accountsStatus->value,
				"changeAccountsStatus",
				"run.log"
			);
			$this->redirect(array('admin'));
		}
	
		$this->render('admin',array(
			"monthOfAccount" => $monthOfAccount,
			"accountsStatus" => $accountsStatus,
			"accountUpdateTime" => $accountUpdateTime
		));
	}

}