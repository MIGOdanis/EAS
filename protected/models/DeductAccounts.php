<?php

/**
 * This is the model class for table "{{deductAccounts}}".
 *
 * The followings are the available columns in table '{{deductAccounts}}':
 * @property integer $id
 * @property integer $supplier_id
 * @property string $reson
 * @property integer $deduct
 * @property integer $create_by
 * @property integer $status
 * @property integer $date
 * @property integer $application_id
 * @property integer $application_by
 * @property integer $application_year
 * @property integer $application_month
 */
class DeductAccounts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{deductAccounts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_id, reson, deduct, create_by, date', 'required'),
			array('supplier_id, deduct, create_by, status, date, application_id, application_by, application_year, application_month', 'numerical', 'integerOnly'=>true),
			array('reson', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplier_id, reson, deduct, create_by, status, date, application_id, application_by, application_year, application_month', 'safe', 'on'=>'search'),
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
			'creater' => array(self::HAS_ONE, 'User', array('id' => 'create_by')),
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
			'supplier_id' => 'Supplier',
			'reson' => '扣款說明',
			'deduct' => '扣款金額',
			'create_by' => 'Create By',
			'status' => 'Status',
			'date' => '扣款日期',
			'application_id' => 'Application',
			'application_by' => 'Application By',
			'application_year' => 'Application Year',
			'application_month' => 'Application Month',
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
	public function search($id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		// $criteria->compare('id',$this->id);
		// $criteria->compare('supplier_id',$this->supplier_id);
		// $criteria->compare('reson',$this->reson,true);
		// $criteria->compare('deduct',$this->deduct);
		// $criteria->compare('create_by',$this->create_by);
		// $criteria->compare('status',$this->status);
		// $criteria->compare('date',$this->date);

		$criteria->addCondition("t.supplier_id = '" . $id . "'");
		// print_r($criteria); exit;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DeductAccounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function countBySupplier($id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'sum(t.deduct) as deduct';
		$criteria->addCondition("t.supplier_id = '" . $id . "'");
		$criteria->addCondition("t.status = '1'");
		$criteria->group = 'supplier_id';
		return $this->find($criteria);
	}

	public function countBySupplierThisApplication($model)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'sum(t.deduct) as deduct';
		$criteria->addCondition("t.supplier_id = '" . $model->supplier_id . "'");
		$criteria->addCondition("application_year = " . date("Y", $model->this_application));	
		$criteria->addCondition("application_month = " . date("m", $model->this_application));	
		$criteria->addCondition("t.status = '0'");
		$criteria->group = 'supplier_id';
		return $this->find($criteria);
	}

}
