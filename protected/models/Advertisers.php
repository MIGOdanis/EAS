<?php

/**
 * This is the model class for table "{{advertisers}}".
 *
 * The followings are the available columns in table '{{advertisers}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $advertiser_name
 * @property string $short_name
 * @property string $site_url
 * @property integer $industry_id
 * @property string $account_id
 * @property string $default_minisite_id
 * @property string $category
 * @property string $remark
 * @property string $organization_code
 * @property integer $source
 * @property string $audit_status
 * @property integer $status
 * @property string $adv_rate
 * @property integer $submit_status
 * @property integer $pre_status
 * @property string $standard_id
 * @property integer $advertiser_type
 * @property integer $sync_time
 */
class Advertisers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{advertisers}}';
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
			array('industry_id, source, status, submit_status, pre_status, advertiser_type, sync_time', 'numerical', 'integerOnly'=>true),
			array('tos_id, account_id, default_minisite_id, audit_status, standard_id', 'length', 'max'=>20),
			array('advertiser_name, short_name, site_url, category, remark, organization_code', 'length', 'max'=>255),
			array('adv_rate', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, advertiser_name, short_name, site_url, industry_id, account_id, default_minisite_id, category, remark, organization_code, source, audit_status, status, adv_rate, submit_status, pre_status, standard_id, advertiser_type, sync_time', 'safe', 'on'=>'search'),
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
			'tos_id' => 'Tos',
			'advertiser_name' => 'Advertiser Name',
			'short_name' => 'Short Name',
			'site_url' => 'Site Url',
			'industry_id' => 'Industry',
			'account_id' => 'Account',
			'default_minisite_id' => 'Default Minisite',
			'category' => 'Category',
			'remark' => 'Remark',
			'organization_code' => 'Organization Code',
			'source' => 'Source',
			'audit_status' => 'Audit Status',
			'status' => 'Status',
			'adv_rate' => 'Adv Rate',
			'submit_status' => 'Submit Status',
			'pre_status' => 'Pre Status',
			'standard_id' => 'Standard',
			'advertiser_type' => 'Advertiser Type',
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
		$criteria->compare('advertiser_name',$this->advertiser_name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('site_url',$this->site_url,true);
		$criteria->compare('industry_id',$this->industry_id);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('default_minisite_id',$this->default_minisite_id,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('organization_code',$this->organization_code,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('audit_status',$this->audit_status,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('adv_rate',$this->adv_rate,true);
		$criteria->compare('submit_status',$this->submit_status);
		$criteria->compare('pre_status',$this->pre_status);
		$criteria->compare('standard_id',$this->standard_id,true);
		$criteria->compare('advertiser_type',$this->advertiser_type);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Advertisers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
