<?php

/**
 * This is the model class for table "{{campaign_budget}}".
 *
 * The followings are the available columns in table '{{campaign_budget}}':
 * @property integer $id
 * @property string $tos_id
 * @property string $campaign_id
 * @property string $total_budget
 * @property string $max_daily_budget
 * @property string $total_pv
 * @property string $max_daily_pv
 * @property string $total_click
 * @property string $max_daily_click
 * @property string $total_viewable
 * @property string $max_daily_viewable
 * @property integer $status
 * @property integer $sync_time
 */
class CampaignBudget extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{campaignBudget}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tos_id, sync_time', 'required'),
			array('status, sync_time', 'numerical', 'integerOnly'=>true),
			array('tos_id, campaign_id, total_pv, max_daily_pv, total_click, max_daily_click, total_viewable, max_daily_viewable', 'length', 'max'=>20),
			array('total_budget, max_daily_budget', 'length', 'max'=>16),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tos_id, campaign_id, total_budget, max_daily_budget, total_pv, max_daily_pv, total_click, max_daily_click, total_viewable, max_daily_viewable, status, sync_time', 'safe', 'on'=>'search'),
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
			'tos_id' => 'Tos',
			'campaign_id' => 'Campaign',
			'total_budget' => 'Total Budget',
			'max_daily_budget' => 'Max Daily Budget',
			'total_pv' => 'Total Pv',
			'max_daily_pv' => 'Max Daily Pv',
			'total_click' => 'Total Click',
			'max_daily_click' => 'Max Daily Click',
			'total_viewable' => 'Total Viewable',
			'max_daily_viewable' => 'Max Daily Viewable',
			'status' => 'Status',
			'sync_time' => 'Sync Time',
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
		$criteria->compare('tos_id',$this->tos_id,true);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('total_budget',$this->total_budget,true);
		$criteria->compare('max_daily_budget',$this->max_daily_budget,true);
		$criteria->compare('total_pv',$this->total_pv,true);
		$criteria->compare('max_daily_pv',$this->max_daily_pv,true);
		$criteria->compare('total_click',$this->total_click,true);
		$criteria->compare('max_daily_click',$this->max_daily_click,true);
		$criteria->compare('total_viewable',$this->total_viewable,true);
		$criteria->compare('max_daily_viewable',$this->max_daily_viewable,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CampaignBudget the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
