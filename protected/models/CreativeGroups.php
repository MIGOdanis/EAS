<?php

/**
 * This is the model class for table "{{creativeGroups}}".
 *
 * The followings are the available columns in table '{{creativeGroups}}':
 * @property string $name
 * @property integer $medium
 * @property string $campaign_id
 * @property string $account_id
 * @property string $ad_format
 * @property string $targeting_url
 * @property string $click_tracking
 * @property string $content_type_id
 * @property string $template_id
 * @property string $adv_feature
 * @property integer $status
 * @property integer $material_delivery
 * @property string $creative_concept_id
 * @property integer $is_default
 * @property integer $source
 * @property integer $channel_type
 * @property integer $media_terminal
 * @property integer $sync_time
 * @property integer $id
 * @property string $tos_id
 */
class CreativeGroups extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{creativeGroups}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sync_time, tos_id', 'required'),
			array('medium, status, material_delivery, is_default, source, channel_type, media_terminal, sync_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('campaign_id, account_id, ad_format, content_type_id, template_id, adv_feature, creative_concept_id, tos_id', 'length', 'max'=>20),
			array('targeting_url', 'length', 'max'=>1024),
			array('click_tracking', 'length', 'max'=>2000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, medium, campaign_id, account_id, ad_format, targeting_url, click_tracking, content_type_id, template_id, adv_feature, status, material_delivery, creative_concept_id, is_default, source, channel_type, media_terminal, sync_time, id, tos_id', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'medium' => 'Medium',
			'campaign_id' => 'Campaign',
			'account_id' => 'Account',
			'ad_format' => 'Ad Format',
			'targeting_url' => 'Targeting Url',
			'click_tracking' => 'Click Tracking',
			'content_type_id' => 'Content Type',
			'template_id' => 'Template',
			'adv_feature' => 'Adv Feature',
			'status' => 'Status',
			'material_delivery' => 'Material Delivery',
			'creative_concept_id' => 'Creative Concept',
			'is_default' => 'Is Default',
			'source' => 'Source',
			'channel_type' => 'Channel Type',
			'media_terminal' => 'Media Terminal',
			'sync_time' => 'Sync Time',
			'id' => 'ID',
			'tos_id' => 'Tos',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('medium',$this->medium);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('ad_format',$this->ad_format,true);
		$criteria->compare('targeting_url',$this->targeting_url,true);
		$criteria->compare('click_tracking',$this->click_tracking,true);
		$criteria->compare('content_type_id',$this->content_type_id,true);
		$criteria->compare('template_id',$this->template_id,true);
		$criteria->compare('adv_feature',$this->adv_feature,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('material_delivery',$this->material_delivery);
		$criteria->compare('creative_concept_id',$this->creative_concept_id,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('source',$this->source);
		$criteria->compare('channel_type',$this->channel_type);
		$criteria->compare('media_terminal',$this->media_terminal);
		$criteria->compare('sync_time',$this->sync_time);
		$criteria->compare('id',$this->id);
		$criteria->compare('tos_id',$this->tos_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreativeGroups the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
