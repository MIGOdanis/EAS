<?php
class TosUpmUser extends CActiveRecord
{
	public $repeat_password;
	public $new_password;	
	public static $conection; 

	/**
	 * @return string the associated database table name
	 */

	public function getDbConnection()
    {
        if (self::$conection !== null)
            return self::$conection;
        else
        {
            self::$conection = Yii::app()->upm;
            if (self::$conection instanceof CDbConnection)
            {
                self::$conection->setActive(true);
                return self::$conection;
            }
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "TosUPM" CDbConnection application component.'));
        }
    }

	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array();
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

		// $criteria=new CDbCriteria;
		// $criteria->compare('id',$this->id);
		// $criteria->compare('user',$this->user,true);
		// $criteria->compare('password',$this->password,true);
		// $criteria->compare('name',$this->name,true);
		// $criteria->compare('auth_id',$this->auth_id);
		// $criteria->compare('group',$this->group);
		// $criteria->compare('creat_time',$this->creat_time);
		// $criteria->compare('active',$this->active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
