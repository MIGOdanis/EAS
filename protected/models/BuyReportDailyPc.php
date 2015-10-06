<?php
class BuyReportDailyPc extends CActiveRecord
{
	public $media_cost_count; 
	public $click_sum;
	public $impression_sum;
	public $income_sum;
	public $temp_income_sum;
	public $temp_advertiser_invoice_sum;
	public $temp_table;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{buyReportDaily}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('settled_time, sync_time,tos_id , report_type', 'required'),
			array('settled_time, media_category_id, screen_pos, adformat, is_outside_tracking, sync_time', 'numerical', 'integerOnly'=>true),
			array('campaign_id, ad_space_id, strategy_id, creative_id, pv, impression, impression_ten_sec, click, media_cost, media_tax_cost, media_ops_cost, income, income_ten_sec, agency_income', 'length', 'max'=>20),
			array('width_height', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, settled_time, campaign_id, ad_space_id, strategy_id, creative_id, media_category_id, screen_pos, adformat, width_height, pv, impression, impression_ten_sec, click, media_cost, media_tax_cost, media_ops_cost, income, income_ten_sec, agency_income, is_outside_tracking, sync_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'adSpace' => array(self::HAS_ONE, 'AdSpace', array('tos_id' => 'ad_space_id')),
			'campaign' => array(self::HAS_ONE, 'Campaign', array('tos_id' => 'campaign_id')),
			'strategy' => array(self::HAS_ONE, 'Strategy', array('tos_id' => 'strategy_id')),
			'creative' => array(self::HAS_ONE, 'CreativeMaterial', array('tos_id' => 'creative_id')),	
			'budget' => array(self::HAS_ONE, 'CampaignBudget', array('campaign_id' => 'campaign_id')),	
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'settled_time' => 'Settled Time',
			'campaign_id' => 'Campaign',
			'ad_space_id' => 'Ad Space',
			'strategy_id' => 'Strategy',
			'creative_id' => 'Creative',
			'media_category_id' => 'Media Category',
			'screen_pos' => 'Screen Pos',
			'adformat' => 'Adformat',
			'width_height' => 'Width Height',
			'pv' => 'Pv',
			'impression' => 'Impression',
			'impression_ten_sec' => 'Impression Ten Sec',
			'click' => 'Click',
			'media_cost' => 'Media Cost',
			'media_tax_cost' => 'Media Tax Cost',
			'media_ops_cost' => 'Media Ops Cost',
			'income' => 'Income',
			'income_ten_sec' => 'Income Ten Sec',
			'agency_income' => 'Agency Income',
			'is_outside_tracking' => 'Is Outside Tracking',
			'sync_time' => 'Sync Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('settled_time',$this->settled_time);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('ad_space_id',$this->ad_space_id,true);
		$criteria->compare('strategy_id',$this->strategy_id,true);
		$criteria->compare('creative_id',$this->creative_id,true);
		$criteria->compare('media_category_id',$this->media_category_id);
		$criteria->compare('screen_pos',$this->screen_pos);
		$criteria->compare('adformat',$this->adformat);
		$criteria->compare('width_height',$this->width_height,true);
		$criteria->compare('pv',$this->pv,true);
		$criteria->compare('impression',$this->impression,true);
		$criteria->compare('impression_ten_sec',$this->impression_ten_sec,true);
		$criteria->compare('click',$this->click,true);
		$criteria->compare('media_cost',$this->media_cost,true);
		$criteria->compare('media_tax_cost',$this->media_tax_cost,true);
		$criteria->compare('media_ops_cost',$this->media_ops_cost,true);
		$criteria->compare('income',$this->income,true);
		$criteria->compare('income_ten_sec',$this->income_ten_sec,true);
		$criteria->compare('agency_income',$this->agency_income,true);
		$criteria->compare('is_outside_tracking',$this->is_outside_tracking);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public  function sumColumn($model,$key){
		$keySum = 0;
		foreach ($model->data as $value) {
			$keySum += $value->$key;
		}

		return $keySum;
	}

	public function addReportTime($criteria, $columns = "settled_time")
	{
		if(!isset($_GET) || $_GET['type'] == "yesterday"){
			$criteria->addCondition($columns ." >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-1 day'))));
		}

		if($_GET['type'] == "7day"){
			$criteria->addCondition($columns ." >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-7 day'))));
		}

		if($_GET['type'] == "30day"){
			$criteria->addCondition($columns ." >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-30 day'))));
		}

		if($_GET['type'] == "pastMonth"){
			$criteria->addCondition($columns ." >= " . strtotime(date("Y-m-01 00:00:00",strtotime("-1 Months"))));
			$criteria->addCondition($columns ." <= " . strtotime(date("Y-m-t 00:00:00",strtotime("-1 Months"))));
		}

		if($_GET['type'] == "thisMonth"){
			$criteria->addCondition($columns ." >= " . strtotime(date("Y-m-01 00:00:00")));
			$criteria->addCondition($columns ." <= " . strtotime(date("Y-m-t 00:00:00")));
		}	

		if($_GET['type'] == "custom"){
			if(isset($_GET['startDay']) && !empty($_GET['startDay'])){
				$criteria->addCondition($columns ." >= " . strtotime($_GET['startDay'] . "00:00:00"));
			}
			if(isset($_GET['endDay']) &&  !empty($_GET['endDay'])){
				$criteria->addCondition($columns ." <= " . strtotime($_GET['endDay'] . "00:00:00"));
			}			

		}

		return $criteria;
	}

	public function addNoPayCampaign($criteria){
		$noPayCriteria = new CDbCriteria;
		$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
		$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
		$noPayCampaignId = array();
		foreach ($noPayCampaign as $value) {
			$noPayCampaignId[] = $value->tos_id;
		}

		if(isset($_GET['showNoPay']) && $_GET['showNoPay'] == "only"){
			$criteria->addInCondition("campaign_id",$noPayCampaignId);
		}else{
			$criteria->addNotInCondition("campaign_id",$noPayCampaignId);
		}
		
		return $criteria;
	}

	//前台供應商查詢
	public function supplierDailyReport($tos_id,$reportType)
	{
		
		$criteria=new CDbCriteria;
		$criteria->addCondition("t.tos_id = '" . $tos_id ."'");
		if($reportType == "supplier"){
			if(isset($_GET['site']) && $_GET['site'] > 0){
				$criteria->addCondition("site.tos_id = '" . $_GET['site'] . "'");
			}

			if(isset($_GET['adSpace']) && $_GET['adSpace'] > 0){
				$criteria->addCondition("adSpace.tos_id = '" . $_GET['adSpace'] . "'");
			}		
		}

		$supplier = Supplier::model()->with("site","site.adSpace")->find($criteria);
		$adSpacArray = array();	

		if($supplier !== null){
			foreach ($supplier->site as $site) {
				foreach ($site->adSpace as $value) {
					$adSpacArray[] = $value->tos_id;
				}
			}				
		}

		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as time
		';

		$criteria = $this->addReportTime($criteria);

		$criteria->addInCondition("ad_space_id",$adSpacArray);
		
		$criteria = $this->addNoPayCampaign($criteria);

		$criteria->with = array("adSpace","adSpace.site","adSpace.site.supplier");

		$criteria->group = "settled_time";

		if(isset($_GET['site']) && $_GET['site'] > 0){

			$criteria->group = "settled_time";

		}elseif(isset($_GET['adSpace']) && $_GET['adSpace'] > 0){

			$criteria->group = "ad_space_id, settled_time";

		}

		if(isset($_GET['export']) && $_GET['export'] == 1){
			$criteria->order = 'impression DESC, click DESC';
			return $this->findAll($criteria);
		}
		
		// print_r($adSpacArray); exit;
		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 'settled_time DESC',
			),			
			'criteria'=>$criteria,
		));
	}





	//後台查詢供應商
	public function adminSupplierDailyReport($adSpacArray)
	{

		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time
		';


		$criteria = $this->addReportTime($criteria);

		if(!empty($adSpacArray)){
			$criteria->addInCondition("ad_space_id",$adSpacArray);
		}

		

		if(isset($_GET['showNoPay']) && $_GET['showNoPay'] != "all"){
			$criteria = $this->addNoPayCampaign($criteria);
		}


		//主要維度
		if(isset($_GET['indexType']) && $_GET['indexType'] == "supplier"){
			// $criteria->addCondition("supplier.tos_id IS NOT NULL");
			$criteria->with = array("adSpace","adSpace.site","adSpace.site.supplier");
			$criteria->group = "supplier.tos_id";
			$order = 'impression DESC, click DESC';
		}

		if(isset($_GET['indexType']) && $_GET['indexType'] == "date"){
			$criteria->group = "t.settled_time";
			$order = 't.settled_time DESC';
		}

		if(isset($_GET['indexType']) && $_GET['indexType'] == "campaign"){
			// $criteria->addCondition("campaign.tos_id IS NOT NULL");
			$criteria->with = array("campaign");
			$criteria->group = "t.campaign_id";
			$order = 'impression DESC, click DESC';
		}

		if(isset($_GET['indexType']) && $_GET['indexType'] == 1){
			$criteria->order = 'impression DESC, click DESC';
			return $this->findAll($criteria);
		}
		
		// print_r($adSpacArray); exit;
		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => $order,
			),			
			'criteria'=>$criteria,
		));
	}

	//後台查詢供應商
	public function adminSiteDailyReport($adSpacArray)
	{
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as time
		';

		$criteria = $this->addReportTime($criteria);

		if(!empty($adSpacArray)){
			$criteria->addInCondition("ad_space_id",$adSpacArray);
		}

		$criteria->addCondition("site.tos_id IS NOT NULL");

		if(isset($_GET['showNoPay']) && $_GET['showNoPay'] != "all"){
			$criteria = $this->addNoPayCampaign($criteria);
		}

		$criteria->with = array("adSpace","adSpace.site","adSpace.site.supplier");

		$criteria->group = "site.tos_id";

		if(isset($_GET['export']) && $_GET['export'] == 1){
			$criteria->order = 'impression DESC, click DESC';
			return $this->findAll($criteria);
		}
		
		// print_r($adSpacArray); exit;
		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 'impression DESC, click DESC',
			),			
			'criteria'=>$criteria,
		));
	}

	//後台查詢供應商
	public function adminAdSpaceDailyReport($adSpacArray)
	{
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as time
		';

		$criteria = $this->addReportTime($criteria);

		if(!empty($adSpacArray)){
			$criteria->addInCondition("ad_space_id",$adSpacArray);
		}

		if(isset($_GET['showNoPay']) && $_GET['showNoPay'] != "all"){
			$criteria = $this->addNoPayCampaign($criteria);
		}		

		$criteria->addCondition("adSpace.tos_id IS NOT NULL");

		$criteria->with = array("adSpace","adSpace.site","adSpace.site.supplier");

		$criteria->group = "adSpace.tos_id";

		if(isset($_GET['export']) && $_GET['export'] == 1){
			$criteria->order = 'impression DESC, click DESC';
			return $this->findAll($criteria);
		}
		
		// print_r($adSpacArray); exit;
		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 'impression DESC, click DESC',
			),			
			'criteria'=>$criteria,
		));
	}

	/*
	======   ================      ===========   ==============   ====
	===    ==    ============   ===   =========   ============   =====
	===   ====   ============   =====   ========   ==========   ======
	===   ====   ============   ======   ========   ========   =======
	===          ============   ======   =========   ======   ========
	===   ====   ============   =====   ===========   ====   =========
	===   ====   ============   ===    =============   ==   ==========
	===   ====   ============      ==================      ===========
	
	廣告主報表

	*/


	//訂單類別報表
	public function supplierCategoryReport($campaignId)
	{
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression
		';

		$criteria = $this->addReportTime($criteria);	

		$criteria->addCondition("campaign_id = '" . $campaignId . "'");

		$criteria->addCondition("category.id IS NOT NULL");

		$criteria->with = array("adSpace","adSpace.site","adSpace.site.category","adSpace.site.category.mediaCategory");

		$criteria->group = "category.category_id";
		
		// print_r($adSpacArray); exit;

		if(isset($_GET['export']) && $_GET['export'] == 1){
			$criteria->order = 't.impression DESC';
			return $this->findAll($criteria);
		}
		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 't.impression DESC',
			),			
			'criteria'=>$criteria,
		));
	}

	//訂單活動報表
	public function campaignBannerReport($campaignId)
	{
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			t.width_height as width_height
		';

		$criteria = $this->addReportTime($criteria);

		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");

		$criteria->addCondition("campaign.tos_id IS NOT NULL");

		$criteria->with = array("adSpace","adSpace.site","adSpace.site.category","adSpace.site.category.mediaCategory","campaign","strategy","creative","creative.creativeGroup");

		$criteria->group = "t.strategy_id, t.creative_id, category.category_id, t.settled_time"; 
		
		// print_r($adSpacArray); exit;

		if(isset($_GET['export']) && $_GET['export'] == 1){
			$criteria->order = 't.settled_time DESC, t.strategy_id DESC, t.creative_id DESC';
			return $this->findAll($criteria);
		}
		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 't.settled_time DESC, t.strategy_id DESC, t.creative_id DESC',
			),			
			'criteria'=>$criteria,
		));
	}

	//經銷對帳
	public function advertiserAccountsReport()
	{
		set_time_limit(0);
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income_sum,
			sum(t.click) as click_sum,
			sum(t.impression) as impression_sum,
			t.*
		';

		$criteria = $this->addReportTime($criteria);


		if(isset($_GET['CampaignId']) && ($_GET['CampaignId'] > 0))
			$criteria->addCondition("t.campaign_id = '" . $_GET['CampaignId'] . "'");

		if(isset($_GET['creater']) && ($_GET['creater'] > 0)){
			$createrCriteria=new CDbCriteria;
			$createrCriteria->addCondition("id = '" . $_GET['creater'] . "' OR parent_id = '" . $_GET['creater'] . "'");
			$creater = TosUpmUser::model()->findAll($createrCriteria);
			$createrArray = array();
			foreach($creater as $value){
				$createrArray[] = $value->id;
			}
			if(is_array($createrArray)){
				$criteria->addInCondition("campaign.create_user",$createrArray);
			}else{
				$criteria->addCondition("campaign.create_user = '" . $_GET['creater'] . "'");	
			}
			
		}

		if(isset($_GET['active']) && ($_GET['active'] > 0))
			$criteria->addCondition("campaign.active = " . ((int)$_GET['active'] - 1));

		$criteria->addCondition("campaign.tos_id IS NOT NULL");
		
		$criteria->with = array("campaign");

		$criteria->group = "t.campaign_id"; 
		
		// print_r($adSpacArray); exit;

		if(isset($_GET['export']) && $_GET['export'] == 1){
			$criteria->order = 't.campaign_id DESC';
			return $this->findAll($criteria);
		}


		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 't.campaign_id DESC',
			),			
			'criteria'=>$criteria,
		));
	}

	//經銷對帳查詢-成本
	public function getCampaignAllIncome($campaign_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'sum(t.income) / 100000 as income_sum';		
		$criteria->addCondition("t.campaign_id = '" . $campaign_id . "'");
		$model = $this->find($criteria);
		$this->temp_income_sum = $model->income_sum;
		return $this->temp_income_sum;
		
	}

	//經銷對帳查詢-發票
	public function getCampaignAdvertiserInvoice($campaign_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'sum(t.price) as price';		
		$criteria->addCondition("t.campaign_id = '" . $campaign_id . "'");
		$criteria->addCondition("t.active = 1");
		$model = AdvertiserInvoice::model()->find($criteria);
		$this->temp_advertiser_invoice_sum = $model->price;
		return $this->temp_advertiser_invoice_sum;
		
	}

	//收視報表
	public function ytbReport($campaignId)
	{

		$criteria=new CDbCriteria;
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$model = CreativeMaterial::model()->findAll($criteria);
		
		$where = array();
		foreach ($model as $value) {
			$where[] =  "`queryStr` LIKE '%" . $value->tos_id . "%' ";
		}

		$criteria=new CDbCriteria;
		if(!empty($where)){
			$where = implode(" OR ", $where);
			$criteria->addCondition($where);
		}else{
			$criteria->addCondition("t.id = 0");
		}

		$this->temp_table = EveTestYtbLogs::model()->ytbReport($criteria);

		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			t.width_height as width_height
		';

		$criteria = $this->addReportTime($criteria);

		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");

		$criteria->addCondition("campaign.tos_id IS NOT NULL");

		$criteria->with = array("adSpace","adSpace.site","adSpace.site.category","adSpace.site.category.mediaCategory","campaign","strategy","creative","creative.creativeGroup");

		$criteria->group = "t.settled_time, t.strategy_id, t.creative_id, category.category_id"; 
		
		$criteria->order = "t.settled_time DESC, t.strategy_id DESC, t.creative_id DESC";

		$model = $this->findAll($criteria);

		$data = array();

		foreach ($model as $value) {
			$data[] = array(
				"settled_time" => $value->settled_time ,
				"campaign" => $value->campaign ,
				"strategy" => $value->strategy ,
				"creative" => $value->creative ,
				"data" => $value ,
				"mediaCategory" => $value->adSpace->site->category->mediaCategory,
				"temp_table" => $this->temp_table[date("Y-m-d",$value->settled_time)][$value->strategy->tos_id][$value->creative->tos_id][$value->adSpace->site->category->mediaCategory->id]
			);
		}

		if(isset($_GET['export']) && $_GET['export'] == 1){
			$criteria->order = 't.settled_time DESC, t.strategy_id DESC, t.creative_id DESC';
			return $data;
		}

		return new CArrayDataProvider($data, array(
			'pagination'=>false
		));	

	}

	//輸出收視報表
	public function exportYtbReport($campaignId)
	{

		$criteria=new CDbCriteria;
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$creative = CreativeMaterial::model()->findAll($criteria);

		$FnWhere = array();
		$YtbWhere = array();
		foreach ($creative as $value) {
			$FnWhere[] =  "`creative` LIKE '%" . $value->tos_id . "%' ";
			$YtbWhere[] =  "`queryStr` LIKE '%" . $value->tos_id . "%' ";
		}

		$criteria=new CDbCriteria;
		if(!empty($FnWhere)){
			$FnWhere = implode(" OR ", $FnWhere);
			$criteria->addCondition($FnWhere);
		}else{
			$criteria->addCondition("t.id = 0");
		}
		
		$functionReport = EveDspLogsDspTosFunc::model()->funcReporByDay($criteria);
		


		$criteria=new CDbCriteria;
		if(!empty($YtbWhere)){
			$YtbWhere = implode(" OR ", $YtbWhere);
			$criteria->addCondition($YtbWhere);
		}else{
			$criteria->addCondition("t.id = 0");
		}
		$ytbReport =  EveTestYtbLogs::model()->ytbReport($criteria);


		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			t.width_height as width_height
		';

		$criteria = $this->addReportTime($criteria);
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$criteria->addCondition("campaign.tos_id IS NOT NULL");
		$criteria->with = array("campaign");
		$criteria->group = "t.settled_time"; 
		$criteria->order = "t.settled_time DESC";

		$model = $this->findAll($criteria);

		$data = array();

		foreach ($model as $value) {
			$data[] = array(
				"settled_time" => $value->settled_time ,
				"campaign" => $value->campaign ,
				"creative" => $value->creative ,
				"data" => $value ,
				"functionReport" => $functionReport[date("Y-m-d",$value->settled_time)],
				"ytbReport" => $ytbReport[date("Y-m-d",$value->settled_time)]
			
			); 
		}

		return $data;

	}

	//輸出收視類別報表
	public function exportYtbCategoryReport($campaignId)
	{


		$criteria=new CDbCriteria;
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$model = CreativeMaterial::model()->findAll($criteria);
		
		$where = array();
		foreach ($model as $value) {
			$where[] =  "`queryStr` LIKE '%" . $value->tos_id . "%' ";
		}

		$criteria=new CDbCriteria;
		if(!empty($where)){
			$where = implode(" OR ", $where);
			$criteria->addCondition($where);
		}else{
			$criteria->addCondition("t.id = 0");
		}

		$ytb = EveTestYtbLogs::model()->ytbCategoryReport($criteria);

		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			t.width_height as width_height
		';

		$criteria = $this->addReportTime($criteria);
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$criteria->addCondition("campaign.tos_id IS NOT NULL");
		$criteria->with = array("adSpace","adSpace.site","adSpace.site.category","adSpace.site.category.mediaCategory","campaign",);
		$criteria->group = "category.category_id"; 
		$criteria->order = "category.category_id";

		$model = $this->findAll($criteria);

		$data = array();

		foreach ($model as $value) {
			$data[] = array(
				"data" => $value,
				"ytb" => $ytb[$value->adSpace->site->category->category_id]
			);
		}

		return $data;

	}

	//加值報表
	public function functionReport($campaignId)
	{

		$criteria=new CDbCriteria;
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$model = CreativeMaterial::model()->findAll($criteria);
		
		$where = array();
		foreach ($model as $value) {
			$where[] =  "`creative` LIKE '%" . $value->tos_id . "%' ";
		}

		$criteria=new CDbCriteria;
		if(!empty($where)){
			$where = implode(" OR ", $where);
			$criteria->addCondition($where);
		}else{
			$criteria->addCondition("t.id = 0");
		}
		
		$this->temp_table = EveDspLogsDspTosFunc::model()->funcReport($criteria);

		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.income) / 100000 as income,
			sum(t.click) as click,
			sum(t.impression) as impression,
			t.width_height as width_height
		';

		$criteria = $this->addReportTime($criteria);
		$criteria->addCondition("t.campaign_id = '" . $campaignId . "'");
		$criteria->addCondition("campaign.tos_id IS NOT NULL");
		$criteria->with = array("adSpace","campaign","creative","creative.creativeGroup");
		$criteria->group = "t.settled_time,creativeGroup.tos_id"; 
		$criteria->order = "t.settled_time DESC";

		$model = $this->findAll($criteria);

		$data = array();

		foreach ($model as $value) {
			$data[] = array(
				"settled_time" => $value->settled_time ,
				"campaign" => $value->campaign ,
				"creative" => $value->creative ,
				"data" => $value ,
				"temp_table" => $this->temp_table[date("Y-m-d",$value->settled_time)][$value->creative->tos_id]
			);
		}

		if(isset($_GET['export']) && $_GET['export'] == 1){
			return $data;
		}

		return new CArrayDataProvider($data, array(
			'pagination'=>false
		));	
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BuyReportDailyPc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
