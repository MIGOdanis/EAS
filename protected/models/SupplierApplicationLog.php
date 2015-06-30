<?php

/**
 * This is the model class for table "{{supplierApplicationLog}}".
 *
 * The followings are the available columns in table '{{supplierApplicationLog}}':
 * @property integer $id
 * @property integer $status
 * @property integer $certificate_status
 * @property integer $certificate_time
 * @property integer $certificate_by
 * @property string $invoice
 * @property integer $invoice_time
 * @property integer $invoice_by
 * @property string $monies
 * @property integer $year
 * @property integer $month
 * @property integer $application_time
 * @property integer $application_by
 * @property integer $supplier_id
 * @property integer $pay_time
 */
class SupplierApplicationLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{supplierApplicationLog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('certificate_by, monies, year, month, application_time, application_by, supplier_id', 'required'),
			array('id, status, certificate_status, certificate_time, certificate_by, invoice_time, invoice_by, year, month, application_time, application_by, supplier_id, pay_time, start_time, end_time', 'numerical', 'integerOnly'=>true),
			array('monies', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lock, status, certificate_status, certificate_time, certificate_by, invoice, invoice_time, invoice_by, monies, year, month, application_time, application_by, supplier_id, pay_time', 'safe', 'on'=>'search'),
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
			'supplier' => array(self::BELONGS_TO, 'Supplier', array('supplier_id' => 'tos_id')),
			'invoiceChecker' => array(self::HAS_ONE, 'User', array('id' => 'invoice_by')),
			'certificateChecker' => array(self::HAS_ONE, 'User', array('id' => 'certificate_by')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'status' => '申請狀態',
			'certificate_status' => '憑證',
			'certificate_time' => 'Certificate Time',
			'certificate_by' => 'Certificate By',
			'invoice' => '請款憑證',
			'invoice_time' => 'Invoice Time',
			'invoice_by' => 'Invoice By',
			'start_time' => '起始月份',
			'end_time' => '最終月份', 
			'monies' => '請款總額',
			'year' => 'Year',
			'month' => 'Month',
			'application_time' => '申請時間',
			'application_by' => 'Application By',
			'supplier_id' => '供應商',
			'pay_time' => '匯款日期',
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
	public function search($isList = false)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$monthOfAccount = SiteSetting::model()->getValByKey("month_of_accounts");

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('status',$this->status);
		$criteria->compare('certificate_status',$this->certificate_status);
		$criteria->compare('certificate_time',$this->certificate_time);
		$criteria->compare('certificate_by',$this->certificate_by);
		$criteria->compare('invoice',$this->invoice,true);
		$criteria->compare('invoice_time',$this->invoice_time);
		$criteria->compare('invoice_by',$this->invoice_by);
		$criteria->compare('monies',$this->monies,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('month',$this->month);
		$criteria->compare('application_time',$this->application_time);
		$criteria->compare('application_by',$this->application_by);
		$criteria->compare('supplier.name',$this->supplier_id,"LIKE","AND",true);
		$criteria->compare('pay_time',$this->pay_time);

		if(isset($_GET['year']) && $_GET['year'] > 0){
			$criteria->addCondition("year = " . (int)$_GET['year']);
		
		}else{
			if(!$isList){
				$criteria->addCondition("year = " . date("Y",strtotime(date("Y-" . date("m",strtotime("-1 Months",$monthOfAccount->value)) . "-01"))));
			}else{
				$criteria->addCondition("year = " . date("Y",$monthOfAccount->value));
			}
			
		}

		if(isset($_GET['month']) && $_GET['month'] > 0){
			$criteria->addCondition("month = " . (int)$_GET['month']);
			
		}else{
			if(!$isList){
				$criteria->addCondition("month = " . date("m",strtotime(date("Y-" . date("m",strtotime("-1 Months",$monthOfAccount->value)) . "-01"))));
			}else{
				$criteria->addCondition("month = " . date("m",$monthOfAccount->value));
			}			
			
		}




		if(isset($_GET['status']) && !empty($_GET['status']))
			$criteria->addCondition("t.status = " . ($_GET['status'] - 1));

		$criteria->with = array("supplier","invoiceChecker","certificateChecker");

		return new CActiveDataProvider($this, array(
			'pagination' => array(
				'pageSize' => 50
			),
			'sort' => array(
				'defaultOrder' => 't.monies DESC',
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierApplicationLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
