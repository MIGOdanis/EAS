<?php

/**
 * This is the model class for table "{{siteApply}}".
 *
 * The followings are the available columns in table '{{siteApply}}':
 * @property integer $id
 * @property string $name
 * @property string $supplier_id
 * @property integer $type
 * @property string $url
 * @property string $summary
 * @property integer $create_time
 * @property integer $status
 * @property integer $apply_by
 */
class SiteApply extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{siteApply}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, supplier_id, create_time, status, apply_by', 'required'),
			array('type, create_time, status, apply_by', 'numerical', 'integerOnly'=>true),
			array('name, url', 'length', 'max'=>255),
			array('supplier_id', 'length', 'max'=>20),
			array('summary', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, supplier_id, type, url, summary, create_time, status, apply_by', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '網站名稱',
			'supplier_id' => '供應商',
			'type' => '網站類型',
			'url' => '網站網址',
			'summary' => '網站簡介',
			'create_time' => 'Create Time',
			'status' => '申請狀態',
			'apply_by' => 'Apply By',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('supplier_id',$this->supplier_id,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('summary',$this->summary,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('apply_by',$this->apply_by);

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
	 * @return SiteApply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
