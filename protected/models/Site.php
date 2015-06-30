<?php

/**
 * This is the model class for table "{{site}}".
 *
 * The followings are the available columns in table '{{site}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $supplier_id
 * @property string $name
 * @property integer $type
 * @property string $domain
 * @property string $description
 * @property integer $create_time
 * @property integer $sync_time
 * @property integer $status
 */
class Site extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{site}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tos_id, supplier_id, type, create_time, status', 'required'),
			array('type, create_time, sync_time, status', 'numerical', 'integerOnly'=>true),
			array('tos_id, supplier_id', 'length', 'max'=>20),
			array('name, domain', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, supplier_id, name, type, domain, description, create_time, sync_time, status', 'safe', 'on'=>'search'),
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
			'supplier' => array(self::HAS_ONE, 'Supplier', array('tos_id' => 'supplier_id')),
			'adSpace' => array(self::HAS_MANY, 'AdSpace', array('site_id' => 'tos_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tos_id' => 'TOS-ID',
			'supplier_id' => '供應商',
			'name' => '網站名稱',
			'type' => '網站類型',
			'domain' => '網域名稱',
			'description' => '說明',
			'create_time' => '建立時間',
			'sync_time' => '同步時間',
			'status' => '狀態(TOS)',
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

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.tos_id',$this->tos_id,true);
		$criteria->compare('supplier.name',$this->supplier_id,true);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.type',$this->type);
		$criteria->compare('t.domain',$this->domain,true);
		$criteria->compare('t.description',$this->description,true);
		$criteria->compare('t.create_time',$this->create_time);
		$criteria->compare('t.sync_time',$this->sync_time);
		$criteria->compare('t.status',$this->status);
		$criteria->with = 'supplier';

		if(isset($_GET['supplier_id']) && $_GET['supplier_id'] > 0)
			$criteria->addCondition("supplier_id = " . (int)$_GET['supplier_id']);

		if(isset($_GET['id']) && $_GET['id'] > 0)
			$criteria->addCondition("t.tos_id = " . (int)$_GET['id']);

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
	 * @return Site the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
