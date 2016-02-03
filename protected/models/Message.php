<?php

/**
 * This is the model class for table "{{message}}".
 *
 * The followings are the available columns in table '{{message}}':
 * @property integer $id
 * @property string $user_id
 * @property string $title
 * @property string $body
 * @property integer $create_by
 * @property integer $create_time
 * @property integer $publish_time
 * @property integer $user_group
 * @property integer $expire_time
 * @property integer $rank
 * @property integer $send_mail
 * @property integer $cron_mail
 * @property integer $active
 */
class Message extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, title, body, create_by, create_time, publish_time, user_group, expire_time, rank, send_mail, cron_mail, active', 'required'),
			array('create_by, create_time, publish_time, user_group, expire_time, rank, send_mail, cron_mail, active', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, title, body, create_by, create_time, publish_time, user_group, expire_time, rank, send_mail, cron_mail, active', 'safe', 'on'=>'search'),
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
			// 'read' => array(self::HAS_ONE, 'MessageRead', array('message_id' => 'id'),'condition'=>'read.user_id = ' . Yii::app()->user->id),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '收件人',
			'title' => '標題',
			'body' => '內文',
			'create_by' => '建立',
			'create_time' => '建立時間',
			'publish_time' => '推播時間',
			'user_group' => '收件群組',
			'expire_time' => '結束時間',
			'rank' => '訊息級別',
			'send_mail' => '發送狀態',
			'cron_mail' => '預約發送',
			'active' => '啟用',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('publish_time',$this->publish_time);
		$criteria->compare('user_group',$this->user_group);
		$criteria->compare('expire_time',$this->expire_time);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('send_mail',$this->send_mail);
		$criteria->compare('cron_mail',$this->cron_mail);
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

	public function supplierMessage($limit=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.user_id LIKE '%:" . Yii::app()->user->id . ":%'");
		$criteria->addCondition("t.active = 1");
		$criteria->addCondition("t.publish_time <= " . time());
		$criteria->addCondition("t.expire_time >= " . time() . " OR t.expire_time = 0");
		
		if($limit)
			$criteria->limit = $limit;

		// print_r($criteria); exit;

		return new CActiveDataProvider($this, array(
			'pagination' => array(
				'pageSize' => 50
			),
			'sort' => array(
				'defaultOrder' => 't.publish_time DESC',
			),
			'criteria'=>$criteria,
		));
	}

	public function getReadStatus($id)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.message_id = :mid");
		$criteria->addCondition("t.user_id = :uid");
		$criteria->params = array(
			':uid' => Yii::app()->user->id,
			':mid' => $id,
		);
		return MessageRead::model()->find($criteria);
	}

	public function getUnReadCount()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("t.user_id = :uid");
		$criteria->params = array(
			':uid' => Yii::app()->user->id,
		);
		$read = MessageRead::model()->count($criteria);

		$criteria = new CDbCriteria;
		$criteria->addCondition("t.user_id LIKE '%:" . Yii::app()->user->id . ":%'");
		$criteria->addCondition("t.active = 1");
		$criteria->addCondition("t.publish_time <= " . time());
		$criteria->addCondition("t.expire_time >= " . time() . " OR t.expire_time = 0");
		$message = Message::model()->count($criteria);

		return $message - $read;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Message the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
