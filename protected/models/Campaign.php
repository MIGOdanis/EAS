<?php

/**
 * This is the model class for table "{{campaign}}".
 *
 * The followings are the available columns in table '{{campaign}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $campaign_name
 * @property string $advertiser_id
 * @property string $account_id
 * @property integer $industry_id
 * @property string $site_id
 * @property string $start_time
 * @property string $end_time
 * @property string $remark
 * @property integer $adv_feature
 * @property integer $source
 * @property integer $status
 * @property string $adv_rate
 * @property string $brand_id
 * @property string $product_id
 * @property string $brief_id
 * @property integer $sync_time
 */
class Campaign extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{campaign}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tos_id, sync_time', 'required'),
			array('industry_id, adv_feature, source, status, sync_time', 'numerical', 'integerOnly'=>true),
			array('tos_id, advertiser_id, account_id, site_id, brand_id, product_id, brief_id', 'length', 'max'=>20),
			array('campaign_name', 'length', 'max'=>256),
			array('remark', 'length', 'max'=>512),
			array('adv_rate', 'length', 'max'=>10),
			array('start_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, campaign_name, advertiser_id, account_id, industry_id, site_id, start_time, end_time, remark, adv_feature, source, status, adv_rate, brand_id, product_id, brief_id, sync_time', 'safe', 'on'=>'search'),
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
			'advertiser' => array(self::HAS_ONE, 'Advertisers', array('tos_id' => 'advertiser_id')),
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
			'campaign_name' => 'Campaign Name',
			'advertiser_id' => 'Advertiser',
			'account_id' => 'Account',
			'industry_id' => 'Industry',
			'site_id' => 'Site',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'remark' => 'Remark',
			'adv_feature' => 'Adv Feature',
			'source' => 'Source',
			'status' => 'Status',
			'adv_rate' => 'Adv Rate',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'brief_id' => 'Brief',
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
		$criteria->compare('campaign_name',$this->campaign_name,true);
		$criteria->compare('advertiser_id',$this->advertiser_id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('industry_id',$this->industry_id);
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('adv_feature',$this->adv_feature);
		$criteria->compare('source',$this->source);
		$criteria->compare('status',$this->status);
		$criteria->compare('adv_rate',$this->adv_rate,true);
		$criteria->compare('brand_id',$this->brand_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('brief_id',$this->brief_id,true);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Campaign the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
