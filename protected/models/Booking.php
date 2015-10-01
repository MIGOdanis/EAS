<?php

/**
 * This is the model class for table "{{booking}}".
 *
 * The followings are the available columns in table '{{booking}}':
 * @property integer $id
 * @property string $campaign_id
 * @property string $strategy_id
 * @property integer $type
 * @property integer $booking_click
 * @property integer $day_click
 * @property integer $run_click
 * @property integer $click_status
 * @property string $booking_imp
 * @property integer $day_imp
 * @property integer $run_imp
 * @property integer $imp_status
 * @property integer $booking_budget
 * @property integer $day_budget
 * @property integer $run_budget
 * @property integer $budget_status
 * @property integer $booking_time
 * @property integer $update_time
 * @property integer $status
 */
class Booking extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{booking}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, strategy_id, type, booking_click, day_click, run_click, booking_imp, day_imp, run_imp, booking_budget, day_budget, run_budget, booking_time, update_time', 'required'),
			array('type, booking_click, day_click, run_click, click_status, day_imp, run_imp, imp_status, booking_budget, day_budget, budget_status, booking_time, update_time, status', 'numerical', 'integerOnly'=>true),
			array('campaign_id, strategy_id, booking_imp', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaign_id, strategy_id, type, booking_click, day_click, run_click, click_status, booking_imp, day_imp, run_imp, imp_status, booking_budget, day_budget, run_budget, budget_status, booking_time, update_time, status', 'safe', 'on'=>'search'),
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
			'campaign' => array(self::HAS_ONE, 'Campaign', array('tos_id' => 'campaign_id')),
			'strategy' => array(self::HAS_ONE, 'Strategy', array('tos_id' => 'strategy_id')),			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'campaign_id' => 'Campaign',
			'strategy_id' => 'Strategy',
			'type' => 'Type',
			'booking_click' => 'Booking Click',
			'day_click' => 'Day Click',
			'run_click' => 'Run Click',
			'click_status' => 'Click Status',
			'booking_imp' => 'Booking Imp',
			'day_imp' => 'Day Imp',
			'run_imp' => 'Run Imp',
			'imp_status' => 'Imp Status',
			'booking_budget' => 'Booking Budget',
			'day_budget' => 'Day Budget',
			'run_budget' => 'Run Budget',
			'budget_status' => 'Budget Status',
			'booking_time' => 'Booking Time',
			'update_time' => 'Update Time',
			'status' => 'Status',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('strategy_id',$this->strategy_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('booking_click',$this->booking_click);
		$criteria->compare('day_click',$this->day_click);
		$criteria->compare('run_click',$this->run_click);
		$criteria->compare('click_status',$this->click_status);
		$criteria->compare('booking_imp',$this->booking_imp,true);
		$criteria->compare('day_imp',$this->day_imp);
		$criteria->compare('run_imp',$this->run_imp);
		$criteria->compare('imp_status',$this->imp_status);
		$criteria->compare('booking_budget',$this->booking_budget);
		$criteria->compare('day_budget',$this->day_budget);
		$criteria->compare('run_budget',$this->run_budget);
		$criteria->compare('budget_status',$this->budget_status);
		$criteria->compare('booking_time',$this->booking_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('status',$this->status);

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

	public function campaignList()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$noPayCampaignId = array();
		if(isset($_GET['resetFilter']) && !isset($_POST['setCampaign'])){
			unset($_COOKIE['noPayCampaignId']);
		}

		if(isset($_POST['setCampaign'])){
			if(!empty($_POST['noPayCampaignId'])){
				$noPayCampaignId = $_POST['noPayCampaignId'];
			}else{

				$noPayCampaignId[] = 1;
			}
			
		}else if(isset($_COOKIE)){
			if(!empty($_COOKIE['noPayCampaignId'])){
				$noPayCampaignId = explode(":", $_COOKIE['noPayCampaignId']);
			}		
		}

		if( empty($noPayCampaignId) ){
			$noPayCriteria = new CDbCriteria;
			$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noBookingAdvertiser']);		
			$noPayCampaign = TosCoreCampaign::model()->findAll($noPayCriteria);
			foreach ($noPayCampaign as $value) {
				$noPayCampaignId[] = $value->id;
			}		
		}

		setcookie("noPayCampaignId", implode(":", $noPayCampaignId), time() + 3600);

		if(isset($_GET['type']) && $_GET['type'] > 0)
			$criteria->addCondition("t.type = " . (int)$_GET['type']);

		$criteria->addNotInCondition("t.campaign_id", $noPayCampaignId);

		$criteria->addCondition("t.status > 0");

		$criteria->with = array("strategy","campaign");

		$day = strtotime($_GET['day'] . "00:00:00");
		// print_r($day); exit;
		$criteria->addCondition("t.booking_time = '" . $day . "'");
		// print_r($criteria); exit;
		return new CActiveDataProvider($this, array(
			'pagination' => false,	
			'criteria'=>$criteria,
		));
	}

	public function campaign()
	{

		$criteria=new CDbCriteria;

		if(isset($_GET['type']) && $_GET['type'] > 0)
			$criteria->addCondition("t.type = " . (int)$_GET['type']);

		$criteria->select = '
			sum(t.booking_click) as booking_click,
			sum(t.day_click) as day_click,
			sum(t.run_click) as run_click,
			sum(t.booking_imp) as booking_imp,
			sum(t.day_imp) as day_imp,
			sum(t.run_imp) as run_imp,
			sum(t.booking_budget) as booking_budget,
			sum(t.day_budget) as day_budget,
			(sum(t.run_budget) ) as run_budget,
			booking_time as booking_time
		';	

		$criteria->addCondition("t.campaign_id = '" . $_GET['id'] . "'");

		$criteria->addCondition("t.status > 0");

		$criteria->with = array("strategy","campaign");

		$criteria->group = "t.booking_time";

		$criteria->order = "booking_time ASC";

		return new CActiveDataProvider($this, array(
			'pagination' => false,	
			'criteria'=>$criteria,
		));
	}

	public function getCampaignChartDate($allData)
	{

		$data = array(array("'日期'","'預估點擊'","'預估曝光'","'預估費用'","'實際點擊'","'實際曝光'","'實際費用'"));
		foreach ($allData->data as $value) {
			$data[] = array(
				"'" . date("Y-m-d", $value->booking_time) . "'",
				$value->day_click,
				$value->day_imp,
				$value->day_budget,
				$value->run_click,
				$value->run_imp,
				$value->run_budget,
			);
		}

		return $data;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Booking the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
