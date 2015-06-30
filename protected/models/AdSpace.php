<?php

/**
 * This is the model class for table "{{adSpace}}".
 *
 * The followings are the available columns in table '{{adSpace}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $site_id
 * @property string $name
 * @property integer $status
 * @property integer $type
 * @property string $ad_format
 * @property string $ratio_id
 * @property integer $def_creative_option
 * @property string $def_creative_id
 * @property integer $adv_feature
 * @property string $material_format
 * @property integer $buy_type
 * @property integer $charge_type
 * @property string $price
 * @property integer $create_time
 * @property string $alias
 * @property string $description
 * @property integer $source
 * @property integer $width
 * @property integer $height
 * @property integer $sync_time
 */
class AdSpace extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{adSpace}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tos_id, site_id, sync_time', 'required'),
			array('status, type, def_creative_option, adv_feature, buy_type, charge_type, create_time, source, width, height, sync_time', 'numerical', 'integerOnly'=>true),
			array('tos_id, site_id, def_creative_id', 'length', 'max'=>20),
			array('name, ad_format, ratio_id, material_format, alias, description', 'length', 'max'=>255),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, site_id, name, status, type, ad_format, ratio_id, def_creative_option, def_creative_id, adv_feature, material_format, buy_type, charge_type, price, create_time, alias, description, source, width, height, sync_time', 'safe', 'on'=>'search'),
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
			'site' => array(self::HAS_ONE, 'Site', array('tos_id' => 'site_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tos_id' => 'TOS_ID',
			'site_id' => '網站',
			'name' => '版位名稱',
			'status' => 'TOS狀態',
			'type' => '版位類型',
			'ad_format' => '廣告型式',
			'ratio_id' => '版位尺寸',
			'def_creative_option' => '默認廣告設定',
			'def_creative_id' => '默認廣告',
			'adv_feature' => 'Adv Feature',
			'material_format' => '素材格式',
			'buy_type' => '採買方式',
			'charge_type' => '計費方式',
			'price' => '價格',
			'create_time' => '建立時間',
			'alias' => '別名',
			'description' => '版位描述',
			'source' => '數據來源',
			'width' => '版位寬',
			'height' => '版位高',
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
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);
		$criteria->compare('ad_format',$this->ad_format,true);
		$criteria->compare('ratio_id',$this->ratio_id,true);
		$criteria->compare('def_creative_option',$this->def_creative_option);
		$criteria->compare('def_creative_id',$this->def_creative_id,true);
		$criteria->compare('adv_feature',$this->adv_feature);
		$criteria->compare('material_format',$this->material_format,true);
		$criteria->compare('buy_type',$this->buy_type);
		$criteria->compare('charge_type',$this->charge_type);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('alias',$this->alias,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);
		$criteria->compare('sync_time',$this->sync_time);
		$criteria->with = 'site';

		if(isset($_GET['site_id']) && $_GET['site_id'] > 0)
			$criteria->addCondition("site_id = " . (int)$_GET['site_id']);

		// print_r($criteria); exit;
		return new CActiveDataProvider($this, array(
			'pagination' => array(
				'pageSize' => 50
			),
			'sort' => array(
				'defaultOrder' => 't.id DESC',
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AdSpace the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
