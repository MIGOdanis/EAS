<?php

/**
 * This is the model class for table "{{supplierApplicationMonies}}".
 *
 * The followings are the available columns in table '{{supplierApplicationMonies}}':
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $site_id
 * @property integer $adSpace_id
 * @property string $total_monies
 * @property string $month_monies
 * @property integer $last_application
 * @property integer $application_type
 * @property integer $application_id
 * @property integer $application_by
 * @property integer $create_time
 * @property integer $update_time
 */
class SupplierApplicationMonies extends CActiveRecord
{

	public $count_monies;
	public $count_deduct;
	public $countAllMonies; //包含年度帳款

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{supplierApplicationMonies}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_id, site_id, adSpace_id, last_application, application_type, application_id, application_by, create_time, update_time', 'required'),
			array('supplier_id, site_id, adSpace_id, last_application, application_type, application_id, application_by, create_time, update_time, this_application', 'numerical', 'integerOnly'=>true),
			array('total_monies, month_monies', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplier_id, site_id, adSpace_id, total_monies, month_monies, last_application, this_application, application_type, application_id, application_by, create_time, update_time', 'safe', 'on'=>'search'),
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
			'supplierMoniesMonthly' => array(self::HAS_ONE, 'SupplierMoniesMonthly', array('adSpace_id' => 'adSpace_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'supplier_id' => '供應商',
			'site_id' => 'Site',
			'adSpace_id' => 'Ad Space',
			'total_monies' => '前期未請款金額',
			'month_monies' => '本月可請款金額',
			'last_application' => '起始請款月份',
			'this_application' => '最後請款月份',
			'count_monies' => '合併可請款金額',
			'application_type' => 'Application Type',
			'application_id' => 'Application',
			'application_by' => 'Application By',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
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
		$criteria->compare('supplier.name',$this->supplier_id,"LIKE","AND",true);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('adSpace_id',$this->adSpace_id);
		$criteria->compare('total_monies',$this->total_monies,true);
		$criteria->compare('month_monies',$this->month_monies,true);
		$criteria->compare('last_application',$this->last_application);
		$criteria->compare('application_type',$this->application_type);
		$criteria->compare('application_id',$this->application_id);
		$criteria->compare('application_by',$this->application_by);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		$criteria->with = array("site", "site.supplier");
		$criteria->group = "supplier_id";

		$criteria->select = '(sum(t.total_monies) + sum(t.month_monies)) as count_monies,
		 sum(t.total_monies) as total_monies,
		 sum(t.month_monies) as month_monies,
		 t.id as id,
		 t.supplier_id as supplier_id,
		 t.site_id as site_id,
		 t.this_application as this_application,
		 t.adSpace_id as adSpace_id,
		 t.last_application as last_application,
		 t.application_type as application_type,
		 t.application_id as application_id,
		 t.application_by as application_by,
		 t.create_time as create_time,
		 t.update_time as update_time';

		return new CActiveDataProvider($this, array(
			'pagination' => false,
			'sort' => array(
				'defaultOrder' => 'count_monies DESC',
			),
			'criteria'=>$criteria,
		));
	}

	public function getSupplierMonies($tos_id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->addCondition("site.supplier_id = " . $tos_id);
		$adSpace = AdSpace::model()->with("site")->findAll($criteria);

		$adSpacArray = array();
		foreach ($adSpace as $value) {
			$adSpacArray[] = $value->tos_id;
		}

		$criteria=new CDbCriteria;
		$criteria->select = '
			(sum(t.total_monies) + sum(t.month_monies)) as count_monies,
			sum(t.total_monies) as total_monies,
			sum(t.month_monies) as month_monies,
			t.id as id,
			t.supplier_id as supplier_id,
			t.site_id as site_id,
			t.this_application as this_application,
			t.adSpace_id as adSpace_id,

			t.last_application as last_application,
			t.application_type as application_type,
			t.application_id as application_id,
			t.application_by as application_by,
			t.create_time as create_time,
			t.update_time as update_time
		';
		$criteria->addInCondition("adSpace_id",$adSpacArray);
		//$criteria->with = array("site", "site.supplier");
		$criteria->group = "supplier_id";


		return $this->find($criteria);
	}

	public function deductAccounts($model)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("supplier_id = " . $model->supplier_id);
		$criteria->addCondition("year = " . date("Y", $model->this_application));	
		$criteria->addCondition("month = " . date("m", $model->this_application));		
		$criteria->addCondition("status = 3");	
		$log = SupplierApplicationLog::model()->find($criteria);
		if($log === null || $log->status == 0){		
			$this->count_deduct = DeductAccounts::model()->countBySupplier($model->supplier_id)->deduct;
		}else{
			$this->count_deduct = DeductAccounts::model()->countBySupplierThisApplication($model)->deduct;
		}
		
		// print_r($this->count_deduct); exit;
	}

	public function yearAccounts($id)
	{
		return SupplierYearAccounts::model()->getYearAccounts($id);
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierApplicationMonies the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
