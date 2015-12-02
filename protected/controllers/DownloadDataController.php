<?php

class DownloadDataController extends Controller
{

		//權限驗證模組
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		
		return $this->checkUserAuth();
	}
	//權限驗證模組
	
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionSiteAdSpaceInfor()
	{
		set_time_limit(0);
		$model = TosCoreAdSpace::model()->with("site", "site.category", 
			"site.category.mediaCategory", "adSpacePricingStrategy", "adSpacePricingStrategy.pricingStrategy"
			, "adSpacePricingStrategy.pricingStrategy.buyerPricingRules")->findAll();
		$data = array();	

		foreach ($model as $value) {

			$pricingStrategyArray = array();
			foreach ($value->adSpacePricingStrategy as $adSpacePricingStrategy) {
				foreach ($adSpacePricingStrategy->pricingStrategy as $pricingStrategy) {
					$pricingStrategyArray[$pricingStrategy->trade_type] = $pricingStrategy->buyerPricingRules;
				}
			}

			$criteria=new CDbCriteria;
			$criteria->addCondition("t.resource_id = " . $value->id);
			$criteria->addCondition("t.status = 1");
			$mediaAdRestriction = TosCoreMediaAdRestriction::model()->with("trafficAdRestrictionRule")->find($criteria);

			// print_r($pricingStrategyArray); exit;
			$data[] = array(
				"A" => $value->site->name,
				"B" => $value->site->category->mediaCategory->name,
				"C" => $value->site->id,
				"D" => $value->site->domain,
				"E" => $value->id,
				"F" => $value->name,
				"G" => (($value->status == 1)?"啟用":"停用"),
				"H" => (($value->site->type == 1) ? $value->width . " x " . $value->height : str_replace (":"," x ",$value->ratio_id)),
				"I" => Yii::app()->params['buyType'][$value->buy_type],
				"J" => ($value->price * Yii::app()->params['priceType'][$value->charge_type]),
				"K" => ((isset($pricingStrategyArray["1"]->cpm_price)) ? $pricingStrategyArray["1"]->cpm_price / 100 : "未填寫"),
				"L" => ((isset($pricingStrategyArray["1"]->cpm_price)) ? $pricingStrategyArray["1"]->cpc_price / 100 : "未填寫"),
				"M" => ((isset($pricingStrategyArray["2"]->cpm_price)) ? $pricingStrategyArray["2"]->cpm_price / 100 : "未填寫"),
				"N" => ((isset($pricingStrategyArray["2"]->cpc_price)) ? $pricingStrategyArray["2"]->cpc_price / 100 : "未填寫"),
				"O" => (($mediaAdRestriction->trafficAdRestrictionRule === null)? "未設置" : $mediaAdRestriction->trafficAdRestrictionRule->permission),
				"P" => (($mediaAdRestriction->trafficAdRestrictionRule === null)? "未設置" : $mediaAdRestriction->trafficAdRestrictionRule->prohibition),
			);
		}

		$report = array(
			"name" => "網站版位資訊",
			"titleName" => "網站版位資訊 資料時間" . date("Y-m-d H:i:s"),
			"fileName" => "網站版位資訊 資料時間" . date("Y-m-d H:i:s"),
			"width" => "P1",
			"title" => array(
				"A2" => "網站名稱",
				"B2" => "網站分類",
				"C2" => "網站ID",
				"D2" => "網站URL",
				"E2" => "版位ID",
				"F2" => "版位名稱",
				"G2" => "狀態",
				"H2" => "尺寸",
				"I2" => "採購類型",
				"J2" => "價格",
				"K2" => "CPM底價",
				"L2" => "CPC底價",
				"M2" => "CPM固定",
				"N2" => "CPC固定",
				"O2" => "定向",
				"P2" => "排除",
			),
			"data" => $data
		);	

		$this->exportExcel($report);
		Yii::app()->end();	
	}

	public function actionStrategyInfor()
	{
		set_time_limit(0);
		$model = TosCoreStrategy::model()->with("campaign", "campaign.industry","strategyBudget")->findAll();

		$data = array();		
		foreach ($model as $value) {

			$criteria=new CDbCriteria;
			$criteria->addCondition("t.strategy_id = " . $value->id);
			$criteria->addCondition("t.status = 1");
			$strategyCap = TosCoreStrategyFrequencyCapping::model()->find($criteria);


			$criteria=new CDbCriteria;
			$criteria->addCondition("t.strategy_id = " . $value->id);
			$criteria->addCondition("t.target_type = 1");
			$criteria->addCondition("t.status = 1");
			$mediaTargeting1 = TosCoreStrategyMediaTargeting::model()->find($criteria);

			$criteria=new CDbCriteria;
			$criteria->addCondition("t.strategy_id = " . $value->id);
			$criteria->addCondition("t.target_type = 2");
			$criteria->addCondition("t.status = 1");
			$mediaTargeting2 = TosCoreStrategyMediaTargeting::model()->find($criteria);

			$data[] = array(
				"A" => $value->campaign->id,
				"B" => $value->campaign->campaign_name,
				"C" => $value->campaign->industry->name,
				"D" => $value->id,
				"E" => $value->strategy_name,
				"F" => Yii::app()->params['strategyBuyMode'][$value->buy_mode],
				"G" => Yii::app()->params['strategyBidType'][$value->bidding_type],
				"H" => ($value->buy_mode == 2) ? Yii::app()->params['strategyChargeType'][$value->charge_type] : Yii::app()->params['strategyKpiType'][$value->kpi_type],
				"I" => $value->strategyBudget->total_budget / 100,
				"J" => $value->strategyBudget->total_imp,
				"K" => $value->strategyBudget->total_click,
				"L" => ($mediaTargeting1 === null)? "未設置" : "有設置",
				"M" => ($mediaTargeting2 === null)? "未設置" : "有設置",
				"N" => Yii::app()->params['strategyPacingType'][$value->pacing_type],
				"O" => $value->priority,
				"P" => ($strategyCap === null)? "未設置" : "有設置",
			);
		}

		$report = array(
			"name" => "訂單策略資訊",
			"titleName" => "訂單策略資訊 資料時間" . date("Y-m-d H:i:s"),
			"fileName" => "訂單策略資訊 資料時間" . date("Y-m-d H:i:s"),
			"width" => "P1",
			"title" => array(
				"A2" => "訂單ID",
				"B2" => "訂單名稱",
				"C2" => "產業名稱",
				"D2" => "策略ID",
				"E2" => "策略名稱",
				"F2" => "購買方式",
				"G2" => "出價策略",
				"H2" => "優化目標",
				"I2" => "預算上限",
				"J2" => "曝光上限",
				"K2" => "點擊上限",
				"L2" => "媒體定向",
				"M2" => "內容定向",
				"N2" => "速度",
				"O2" => "優先級",
				"P2" => "頻次設定",			
			),
			"data" => $data
		);	

		$this->exportExcel($report);
		Yii::app()->end();	
	}	

	public function actionCreativeInfor()
	{
		set_time_limit(0);
		$model = TosCoreCreativeMaterial::model()->with("campaign","group")->findAll();

		$data = array();		
		foreach ($model as $value) {
			$elandCat = TosCoreIndustryCategory::model()->elandFunction($value->campaign->industry->id);
			$data[] = array(
				"A" => $value->campaign->id,
				"B" => $value->campaign->campaign_name,
				"C" => $value->campaign->industry->name,
				"D" => $elandCat,
				"E" => $value->group->id,
				"F" => $value->id,
				"G" => $value->group->name,
			);
		}

		$report = array(
			"name" => "訂單素材資訊",
			"titleName" => "訂單素材資訊 資料時間" . date("Y-m-d H:i:s"),
			"fileName" => "訂單素材資訊 資料時間" . date("Y-m-d H:i:s"),
			"width" => "F1",
			"title" => array(
				"A2" => "訂單ID",
				"B2" => "訂單名稱",
				"C2" => "產業名稱",
				"D2" => "產業名稱(意藍)",
				"E2" => "素材群組ID (後台)",
				"F2" => "素材ID(前台)",
				"G2" => "素材名稱",
			),
			"data" => $data
		);	

		$this->exportExcel($report);
		Yii::app()->end();	
	}




}