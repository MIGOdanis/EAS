<?php

/**
 * This is the model class for table "{{campaignBookingHistory}}".
 *
 * The followings are the available columns in table '{{campaignBookingHistory}}':
 * @property integer $id
 * @property string $campaign_id
 * @property integer $booking_day
 * @property integer $remaining_day
 * @property integer $booking_click
 * @property integer $remaining_click
 * @property integer $day_click
 * @property integer $run_click
 * @property integer $click_status
 * @property string $booking_imp
 * @property string $remaining_imp
 * @property integer $day_imp
 * @property integer $run_imp
 * @property integer $imp_status
 * @property integer $booking_budget
 * @property integer $remaining_budget
 * @property integer $day_budget
 * @property integer $run_budget
 * @property integer $history_time
 */
class CampaignBookingHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{campaignBookingHistory}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, booking_day, remaining_day, booking_click, remaining_click, day_click, run_click, booking_imp, remaining_imp, day_imp, run_imp, booking_budget, remaining_budget, day_budget, run_budget, history_time', 'required'),
			array('booking_day, remaining_day, booking_click, remaining_click, day_click, run_click, click_status, day_imp, run_imp, imp_status, booking_budget, remaining_budget, day_budget, run_budget, history_time', 'numerical', 'integerOnly'=>true),
			array('campaign_id, booking_imp, remaining_imp', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaign_id, booking_day, remaining_day, booking_click, remaining_click, day_click, run_click, click_status, booking_imp, remaining_imp, day_imp, run_imp, imp_status, booking_budget, remaining_budget, day_budget, run_budget, history_time', 'safe', 'on'=>'search'),
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
			'run_click' => 'Run Click',
			'click_status' => 'Click Status',
			'booking_imp' => 'Booking Imp',
			'remaining_imp' => 'Remaining Imp',
			'day_imp' => 'Day Imp',
			'run_imp' => 'Run Imp',
			'imp_status' => 'Imp Status',
			'booking_budget' => 'Booking Budget',
			'remaining_budget' => 'Remaining Budget',
			'day_budget' => 'Day Budget',
			'run_budget' => 'Run Budget',
			'history_time' => 'History Time',
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
		$criteria->compare('run_click',$this->run_click);
		$criteria->compare('click_status',$this->click_status);
		$criteria->compare('booking_imp',$this->booking_imp,true);
		$criteria->compare('remaining_imp',$this->remaining_imp,true);
		$criteria->compare('day_imp',$this->day_imp);
		$criteria->compare('run_imp',$this->run_imp);
		$criteria->compare('imp_status',$this->imp_status);
		$criteria->compare('booking_budget',$this->booking_budget);
		$criteria->compare('remaining_budget',$this->remaining_budget);
		$criteria->compare('day_budget',$this->day_budget);
		$criteria->compare('run_budget',$this->run_budget);
		$criteria->compare('history_time',$this->history_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CampaignBookingHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
