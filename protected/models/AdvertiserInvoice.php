<?php

/**
 * This is the model class for table "{{advertiserInvoice}}".
 *
 * The followings are the available columns in table '{{advertiserInvoice}}':
 * @property integer $id
 * @property string $campaign_id
 * @property integer $time
 * @property integer $price
 * @property string $number
 * @property integer $create_time
 * @property integer $create_by
 * @property string $remark
 */
class AdvertiserInvoice extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{advertiserInvoice}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, time, price, number, create_time, create_by', 'required'),
			array('time, price, create_time, create_by', 'numerical', 'integerOnly'=>true),
			array('campaign_id', 'length', 'max'=>20),
			array('number', 'length', 'max'=>255),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaign_id, time, price, number, create_time, create_by, active, remark', 'safe', 'on'=>'search'),
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
			'invoiceCreater' => array(self::HAS_ONE, 'User', array('id' => 'create_by')),
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
			'time' => '發票時間',
			'price' => '發票金額',
			'number' => '發票號碼',
			'create_time' => 'Create Time',
			'create_by' => 'Create By',
			'remark' => '備註',
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
	public function search($campaign_id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('price',$this->price);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('remark',$this->remark,true);


		$criteria->addCondition("t.campaign_id = '" . $campaign_id . "'");

		$criteria->with = array("campaign","invoiceCreater");

		return new CActiveDataProvider($this, array(
			'pagination' => false,
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
	 * @return AdvertiserInvoice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
