<?php

/**
 * This is the model class for table "{{advertiserReceivables}}".
 *
 * The followings are the available columns in table '{{advertiserReceivables}}':
 * @property integer $id
 * @property string $campaign_id
 * @property string $year
 * @property string $month
 * @property integer $time
 * @property integer $price
 * @property integer $create_time
 * @property integer $create_by
 * @property string $remark
 * @property integer $active
 */
class AdvertiserReceivables extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{advertiserReceivables}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, year, month, price, create_time, create_by', 'required'),
			array('price, create_time, create_by, active', 'numerical', 'integerOnly'=>true),
			array('campaign_id', 'length', 'max'=>20),
			array('year', 'length', 'max'=>4),
			array('month', 'length', 'max'=>2),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, campaign_id, year, month, price, create_time, create_by, remark, active', 'safe', 'on'=>'search'),
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
			'receivablesCreater' => array(self::HAS_ONE, 'User', array('id' => 'create_by')),
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
			'year' => 'Year',
			'month' => 'Month',
			'price' => '認列額度',
			'create_time' => 'Create Time',
			'create_by' => 'Create By',
			'remark' => '備註',
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
	public function search($campaign_id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('year',$this->year,true);
		$criteria->compare('month',$this->month,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('active',$this->active);

		$criteria->addCondition("t.campaign_id = '" . $campaign_id . "'");

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
	 * @return AdvertiserReceivables the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//經銷對帳查詢-發票
	public function getCampaignAdvertiserReceivables($campaign_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = 'sum(t.price) as price';		
		$criteria->addCondition("t.campaign_id = '" . $campaign_id . "'");
		$criteria->addCondition("t.active = 1");
		$model = $this->find($criteria);
		return $model->price;
		
	}

}
