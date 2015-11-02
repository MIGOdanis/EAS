<?php

/**
 * This is the model class for table "{{campaignBooking}}".
 *
 * The followings are the available columns in table '{{campaignBooking}}':
 * @property integer $id
 * @property string $campaign_id
 * @property integer $booking_day
 * @property integer $remaining_day
 * @property integer $booking_click
 * @property integer $remaining_click
 * @property integer $day_click
 * @property integer $booking_imp
 * @property integer $remaining_imp
 * @property integer $day_imp
 * @property integer $booking_budget
 * @property integer $remaining_budget
 * @property integer $day_budget
 * @property integer $sync_time
 */
class CampaignBooking extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{campaignBooking}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, booking_day, remaining_day, booking_click, remaining_click, day_click, booking_imp, remaining_imp, day_imp, booking_budget, remaining_budget, day_budget, sync_time', 'required'),
			array('booking_day, remaining_day, booking_click, remaining_click, day_click, booking_imp, remaining_imp, day_imp, booking_budget, remaining_budget, day_budget, sync_time', 'numerical', 'integerOnly'=>true),
			array('campaign_id', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaign_id, booking_day, remaining_day, booking_click, remaining_click, day_click, booking_imp, remaining_imp, day_imp, booking_budget, remaining_budget, day_budget, sync_time', 'safe', 'on'=>'search'),
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
			'booking_day' => 'Booking Day',
			'remaining_day' => 'Remaining Day',
			'booking_click' => 'Booking Click',
			'remaining_click' => 'Remaining Click',
			'day_click' => 'Day Click',
			'booking_imp' => 'Booking Imp',
			'remaining_imp' => 'Remaining Imp',
			'day_imp' => 'Day Imp',
			'booking_budget' => 'Booking Budget',
			'remaining_budget' => 'Remaining Budget',
			'day_budget' => 'Day Budget',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('booking_day',$this->booking_day);
		$criteria->compare('remaining_day',$this->remaining_day);
		$criteria->compare('booking_click',$this->booking_click);
		$criteria->compare('remaining_click',$this->remaining_click);
		$criteria->compare('day_click',$this->day_click);
		$criteria->compare('booking_imp',$this->booking_imp);
		$criteria->compare('remaining_imp',$this->remaining_imp);
		$criteria->compare('day_imp',$this->day_imp);
		$criteria->compare('booking_budget',$this->booking_budget);
		$criteria->compare('remaining_budget',$this->remaining_budget);
		$criteria->compare('day_budget',$this->day_budget);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function campaignList()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;
		$criteria->addCondition("t.start_time <= '" . time() ."'");
		$criteria->addCondition("t.end_time >= '" . time() ."'");

		if(isset($_GET['resetFilter'])){
			unset($_COOKIE['noPayCampaignId']);
		}

		$noPayCampaignId = array();

		if(isset($_POST['noPayCampaignId']) && !empty($_POST['noPayCampaignId'])){
			$noPayCampaignId = $_POST['noPayCampaignId'];
		}else if(isset($_COOKIE['noPayCampaignId']) && !empty($_COOKIE['noPayCampaignId'])){
			$noPayCampaignId = explode(":", $_COOKIE['noPayCampaignId']);
		}

		if(empty($noPayCampaignId)){
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

		$criteria->with = array("strategy","campaign");

		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 'remaining_day ASC',
			),			
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CampaignBooking the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
