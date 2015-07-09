<?php

/**
 * This is the model class for table "{{creativeMaterial}}".
 *
 * The followings are the available columns in table '{{creativeMaterial}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $name
 * @property string $creative_group_id
 * @property string $campaign_id
 * @property string $account_id
 * @property string $adv_feature
 * @property string $size_id
 * @property integer $width
 * @property integer $height
 * @property string $material_format
 * @property integer $play_time
 * @property integer $status
 * @property integer $category
 * @property string $material_url
 * @property string $material_content
 * @property integer $material_type
 * @property integer $size
 * @property integer $is_mraid
 * @property integer $sync_time
 */
class CreativeMaterial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{creativeMaterial}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tos_id, campaign_id, width, height, status, sync_time', 'required'),
			array('width, height, play_time, status, category, material_type, size, is_mraid, sync_time', 'numerical', 'integerOnly'=>true),
			array('tos_id, creative_group_id, campaign_id, account_id, adv_feature, size_id', 'length', 'max'=>20),
			array('name, material_url', 'length', 'max'=>255),
			array('material_format', 'length', 'max'=>50),
			array('material_content', 'length', 'max'=>20000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, name, creative_group_id, campaign_id, account_id, adv_feature, size_id, width, height, material_format, play_time, status, category, material_url, material_content, material_type, size, is_mraid, sync_time', 'safe', 'on'=>'search'),
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
			'creativeGroup' => array(self::HAS_ONE, 'CreativeGroups', array('tos_id' => 'creative_group_id')),	
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
			'name' => 'Name',
			'creative_group_id' => 'Creative Group',
			'campaign_id' => 'Campaign',
			'account_id' => 'Account',
			'adv_feature' => 'Adv Feature',
			'size_id' => 'Size',
			'width' => 'Width',
			'height' => 'Height',
			'material_format' => 'Material Format',
			'play_time' => 'Play Time',
			'status' => 'Status',
			'category' => 'Category',
			'material_url' => 'Material Url',
			'material_content' => 'Material Content',
			'material_type' => 'Material Type',
			'size' => 'Size',
			'is_mraid' => 'Is Mraid',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('creative_group_id',$this->creative_group_id,true);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('account_id',$this->account_id,true);
		$criteria->compare('adv_feature',$this->adv_feature,true);
		$criteria->compare('size_id',$this->size_id,true);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);
		$criteria->compare('material_format',$this->material_format,true);
		$criteria->compare('play_time',$this->play_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('category',$this->category);
		$criteria->compare('material_url',$this->material_url,true);
		$criteria->compare('material_content',$this->material_content,true);
		$criteria->compare('material_type',$this->material_type);
		$criteria->compare('size',$this->size);
		$criteria->compare('is_mraid',$this->is_mraid);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreativeMaterial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
