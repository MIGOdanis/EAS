<?php

/**
 * This is the model class for table "{{authGroup}}".
 *
 * The followings are the available columns in table '{{authGroup}}':
 * @property integer $id
 * @property string $name
 * @property integer $group_id
 * @property string $auth
 * @property integer $creat_time
 * @property integer $creat_by
 * @property integer $update_time
 * @property integer $update_by
 * @property integer $active
 */
class AuthGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{authGroup}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, group_id, auth, creat_time, creat_by, update_time, update_by, active', 'required'),
			array('group_id, creat_time, creat_by, update_time, update_by, active', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, group_id, auth, creat_time, creat_by, update_time, update_by, active', 'safe', 'on'=>'search'),
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
			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '權限組名稱',
			'group_id' => '使用群組',
			'auth' => '權限組內容',
			'creat_time' => 'Creat Time',
			'creat_by' => 'Creat By',
			'update_time' => 'Update Time',
			'update_by' => 'Update By',
			'active' => 'Active',
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
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('auth',$this->auth,true);
		$criteria->compare('creat_time',$this->creat_time);
		$criteria->compare('creat_by',$this->creat_by);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('active',$this->active);

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
	 * @return AuthGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
