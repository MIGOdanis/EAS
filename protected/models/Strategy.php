<?php

/**
 * This is the model class for table "{{strategy}}".
 *
 * The followings are the available columns in table '{{strategy}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $strategy_name
 * @property integer $strategy_type
 * @property integer $medium
 * @property string $campaign_id
 * @property string $tags
 * @property integer $buy_mode
 * @property integer $bidding_type
 * @property string $bidding_price
 * @property integer $kpi_type
 * @property string $kpi_value
 * @property integer $sec_kpi_type
 * @property string $sec_kpi_value
 * @property integer $charge_type
 * @property integer $priority
 * @property integer $weight
 * @property integer $pacing_type
 * @property string $charge_price
 * @property string $imp_tracking
 * @property integer $status
 * @property string $creative_tag
 * @property integer $start_time
 * @property integer $end_time
 * @property string $adv_feature
 * @property string $ops_rate
 * @property string $account_id
 * @property integer $range_type
 * @property string $range_price
 * @property integer $bidding_strategy
 * @property integer $sync_time
 */
class Strategy extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{strategy}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tos_id, start_time, end_time, sync_time', 'required'),
			array('strategy_type, medium, buy_mode, bidding_type, kpi_type, sec_kpi_type, charge_type, priority, weight, pacing_type, status, start_time, end_time, range_type, bidding_strategy, sync_time', 'numerical', 'integerOnly'=>true),
			array('tos_id, campaign_id, adv_feature, account_id', 'length', 'max'=>20),
			array('strategy_name, creative_tag', 'length', 'max'=>255),
			array('tags, imp_tracking', 'length', 'max'=>512),
			array('bidding_price', 'length', 'max'=>12),
			array('kpi_value, sec_kpi_value, ops_rate', 'length', 'max'=>10),
			array('charge_price, range_price', 'length', 'max'=>16),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, strategy_name, strategy_type, medium, campaign_id, tags, buy_mode, bidding_type, bidding_price, kpi_type, kpi_value, sec_kpi_type, sec_kpi_value, charge_type, priority, weight, pacing_type, charge_price, imp_tracking, status, creative_tag, start_time, end_time, adv_feature, ops_rate, account_id, range_type, range_price, bidding_strategy, sync_time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tos_id' => 'Tos',
			'strategy_name' => 'Strategy Name',
			'strategy_type' => 'Strategy Type',
			'medium' => 'Medium',
			'campaign_id' => 'Campaign',
			'tags' => 'Tags',
			'buy_mode' => 'Buy Mode',
			'bidding_type' => 'Bidding Type',
			'bidding_price' => 'Bidding Price',
			'kpi_type' => 'Kpi Type',
			'kpi_value' => 'Kpi Value',
			'sec_kpi_type' => 'Sec Kpi Type',
			'sec_kpi_value' => 'Sec Kpi Value',
			'charge_type' => 'Charge Type',
			'priority' => 'Priority',
			'weight' => 'Weight',
			'pacing_type' => 'Pacing Type',
			'charge_price' => 'Charge Price',
			'imp_tracking' => 'Imp Tracking',
			'status' => 'Status',
			'creative_tag' => 'Creative Tag',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'adv_feature' => 'Adv Feature',
			'ops_rate' => 'Ops Rate',
			'account_id' => 'Account',
			'range_type' => 'Range Type',
			'range_price' => 'Range Price',
			'bidding_strategy' => 'Bidding Strategy',
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
		$criteria->compare('tos_id',$this->tos_id,true);
		$criteria->compare('strategy_name',$this->strategy_name,true);
		$criteria->compare('strategy_type',$this->strategy_type);
		$criteria->compare('medium',$this->medium);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('buy_mode',$this->buy_mode);
		$criteria->compare('bidding_type',$this->bidding_type);
		$criteria->compare('bidding_price',$this->bidding_price,true);
		$criteria->compare('kpi_type',$this->kpi_type);
		$criteria->compare('kpi_value',$this->kpi_value,true);
		$criteria->compare('sec_kpi_type',$this->sec_kpi_type);
		$criteria->compare('sec_kpi_value',$this->sec_kpi_value,true);
		$criteria->compare('charge_type',$this->charge_type);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('pacing_type',$this->pacing_type);
		$criteria->compare('charge_price',$this->charge_price,true);
		$criteria->compare('imp_tracking',$this->imp_tracking,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('creative_tag',$this->creative_tag,true);
		$criteria->compare('start_time',$this->start_time);
		$criteria->compare('end_time',$this->end_time);
		$criteria->compare('adv_feature',$this->adv_feature,true);
		$criteria->compare('ops_rate',$this->ops_rate,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('range_type',$this->range_type);
		$criteria->compare('range_price',$this->range_price,true);
		$criteria->compare('bidding_strategy',$this->bidding_strategy);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Strategy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
