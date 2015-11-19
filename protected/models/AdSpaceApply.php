<?php

/**
 * This is the model class for table "{{adSpaceApply}}".
 *
 * The followings are the available columns in table '{{adSpaceApply}}':
 * @property integer $id
 * @property string $size
 * @property string $site_id
 * @property string $position
 * @property string $url
 * @property string $imp
 * @property string $ctr
 * @property integer $is_only
 * @property string $other_network
 * @property string $remark
 * @property integer $status
 * @property integer $apply_by
 * @property integer $create_time
 */
class AdSpaceApply extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{adSpaceApply}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('size, site_id, position, status, apply_by, create_time', 'required'),
			array('is_only, status, apply_by, create_time', 'numerical', 'integerOnly'=>true),
			array('site_id', 'length', 'max'=>20),
			array('position, url, imp, ctr, other_network', 'length', 'max'=>255),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, size, site_id, position, url, imp, ctr, is_only, other_network, remark, status, apply_by, create_time', 'safe', 'on'=>'search'),
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
			'site' => array(self::HAS_ONE, 'Site', array('tos_id' => 'site_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'size' => '尺寸',
			'site_id' => '網站編號',
			'position' => '位置',
			'url' => '版位網址',
			'imp' => '曝光量',
			'ctr' => '參考CTR',
			'is_only' => '是否輪播',
			'other_network' => '其他聯播網',
			'remark' => '備註',
			'status' => 'Status',
			'apply_by' => 'Apply By',
			'create_time' => 'Create Time',
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
		$criteria->compare('size',$this->size,true);
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('imp',$this->imp,true);
		$criteria->compare('ctr',$this->ctr,true);
		$criteria->compare('is_only',$this->is_only);
		$criteria->compare('other_network',$this->other_network,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('apply_by',$this->apply_by);
		$criteria->compare('create_time',$this->create_time);

		$criteria->with = 'site';

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
	 * @return AdSpaceApply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
