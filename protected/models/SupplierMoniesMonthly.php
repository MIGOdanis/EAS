<?php

/**
 * This is the model class for table "{{supplierMoniesMonthly}}".
 *
 * The followings are the available columns in table '{{supplierMoniesMonthly}}':
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $site_id
 * @property integer $adSpace_id
 * @property string $total_monies
 * @property integer $imp
 * @property integer $click
 * @property integer $year
 * @property integer $month
 * @property integer $buy_type
 * @property integer $charge_type
 * @property string $price
 */
class SupplierMoniesMonthly extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{supplierMoniesMonthly}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_id, site_id, adSpace_id, imp, click, year, month, buy_type, charge_type, price', 'required'),
			array('supplier_id, site_id, adSpace_id, imp, click, year, month, buy_type, charge_type', 'numerical', 'integerOnly'=>true),
			array('total_monies', 'length', 'max'=>20),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplier_id, site_id, adSpace_id, total_monies, imp, click, year, month, buy_type, charge_type, price', 'safe', 'on'=>'search'),
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
			'site' => array(self::HAS_ONE, 'Site', array('tos_id' => 'site_id')),
			'adSpace' => array(self::HAS_ONE, 'AdSpace', array('tos_id' => 'adSpace_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'supplier_id' => 'Supplier',
			'site_id' => 'Site',
			'adSpace_id' => 'Ad Space',
			'total_monies' => 'Total Monies',
			'imp' => 'Imp',
			'click' => 'Click',
			'year' => 'Year',
			'month' => 'Month',
			'buy_type' => 'Buy Type',
			'charge_type' => 'Charge Type',
			'price' => 'Price',
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
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('adSpace_id',$this->adSpace_id);
		$criteria->compare('total_monies',$this->total_monies,true);
		$criteria->compare('imp',$this->imp);
		$criteria->compare('click',$this->click);
		$criteria->compare('year',$this->year);
		$criteria->compare('month',$this->month);
		$criteria->compare('buy_type',$this->buy_type);
		$criteria->compare('charge_type',$this->charge_type);
		$criteria->compare('price',$this->price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierMoniesMonthly the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
