<?php

/**
 * This is the model class for table "{{supplierYearAccounts}}".
 *
 * The followings are the available columns in table '{{supplierYearAccounts}}':
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $site_id
 * @property integer $adSpace_id
 * @property string $total_monies
 * @property integer $last_application
 * @property integer $this_application
 * @property integer $year
 * @property integer $application_type
 * @property integer $application_id
 * @property integer $application_by
 * @property integer $create_time
 * @property integer $update_time
 */
class SupplierYearAccounts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{supplierYearAccounts}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_id, site_id, adSpace_id, last_application, year, application_type, application_id, application_by, create_time, update_time', 'required'),
			array('supplier_id, site_id, adSpace_id, last_application, this_application, year, application_type, application_id, application_by, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('total_monies', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplier_id, site_id, adSpace_id, total_monies, last_application, this_application, year, application_type, application_id, application_by, create_time, update_time', 'safe', 'on'=>'search'),
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
			'supplier_id' => '供應商',
			'site_id' => 'Site',
			'adSpace_id' => 'Ad Space',
			'total_monies' => 'Total Monies',
			'last_application' => 'Last Application',
			'this_application' => 'This Application',
			'year' => 'Year',
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
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('adSpace_id',$this->adSpace_id);
		$criteria->compare('total_monies',$this->total_monies,true);
		$criteria->compare('last_application',$this->last_application);
		$criteria->compare('this_application',$this->this_application);
		$criteria->compare('year',$this->year);
		$criteria->compare('application_type',$this->application_type);
		$criteria->compare('application_id',$this->application_id);
		$criteria->compare('application_by',$this->application_by);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		$criteria->with = array("site", "supplier", "adSpace");

		if(isset($_GET['year']) && $_GET['application_type'] > 0)
			$criteria->addCondition("year = " . (int)$_GET['year']);

		if(isset($_GET['application_type']) && $_GET['application_type'] > 0)
			$criteria->addCondition("application_type = " . ((int)$_GET['application_type'] - 1));		



		if (isset($_GET['export'])) {
			$criteria->order = 't.supplier_id DESC';
			return $this->findAll($criteria);
		}

		$criteria->group = "supplier_id";
		$criteria->select = '
		 sum(t.total_monies) as total_monies,
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
				'defaultOrder' => 'total_monies DESC',
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierYearAccounts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getYearAccounts($id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = '
		 sum(t.total_monies) as total_monies,
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
		$criteria->addCondition("supplier_id = " . $id);
		$criteria->addCondition("application_type != 2");
		$criteria->group = "supplier_id";
		return $this->find($criteria);	
	}	

}
