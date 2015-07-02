<?php

/**
 * This is the model class for table "{{buyReportDailyPc}}".
 *
 * The followings are the available columns in table '{{buyReportDailyPc}}':
 * @property string $id
 * @property integer $settled_time
 * @property string $campaign_id
 * @property string $ad_space_id
 * @property string $strategy_id
 * @property string $creative_id
 * @property integer $media_category_id
 * @property integer $screen_pos
 * @property integer $adformat
 * @property string $width_height
 * @property string $pv
 * @property string $impression
 * @property string $impression_ten_sec
 * @property string $click
 * @property string $media_cost
 * @property string $media_tax_cost
 * @property string $media_ops_cost
 * @property string $income
 * @property string $income_ten_sec
 * @property string $agency_income
 * @property integer $is_outside_tracking
 * @property integer $sync_time
 */
class BuyReportDailyPc extends CActiveRecord
{
	public $media_cost_count; 
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{buyReportDailyPc}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('settled_time, sync_time', 'required'),
			array('settled_time, media_category_id, screen_pos, adformat, is_outside_tracking, sync_time', 'numerical', 'integerOnly'=>true),
			array('campaign_id, ad_space_id, strategy_id, creative_id, pv, impression, impression_ten_sec, click, media_cost, media_tax_cost, media_ops_cost, income, income_ten_sec, agency_income', 'length', 'max'=>20),
			array('width_height', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, settled_time, campaign_id, ad_space_id, strategy_id, creative_id, media_category_id, screen_pos, adformat, width_height, pv, impression, impression_ten_sec, click, media_cost, media_tax_cost, media_ops_cost, income, income_ten_sec, agency_income, is_outside_tracking, sync_time', 'safe', 'on'=>'search'),
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
			'settled_time' => 'Settled Time',
			'campaign_id' => 'Campaign',
			'ad_space_id' => 'Ad Space',
			'strategy_id' => 'Strategy',
			'creative_id' => 'Creative',
			'media_category_id' => 'Media Category',
			'screen_pos' => 'Screen Pos',
			'adformat' => 'Adformat',
			'width_height' => 'Width Height',
			'pv' => 'Pv',
			'impression' => 'Impression',
			'impression_ten_sec' => 'Impression Ten Sec',
			'click' => 'Click',
			'media_cost' => 'Media Cost',
			'media_tax_cost' => 'Media Tax Cost',
			'media_ops_cost' => 'Media Ops Cost',
			'income' => 'Income',
			'income_ten_sec' => 'Income Ten Sec',
			'agency_income' => 'Agency Income',
			'is_outside_tracking' => 'Is Outside Tracking',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('settled_time',$this->settled_time);
		$criteria->compare('campaign_id',$this->campaign_id,true);
		$criteria->compare('ad_space_id',$this->ad_space_id,true);
		$criteria->compare('strategy_id',$this->strategy_id,true);
		$criteria->compare('creative_id',$this->creative_id,true);
		$criteria->compare('media_category_id',$this->media_category_id);
		$criteria->compare('screen_pos',$this->screen_pos);
		$criteria->compare('adformat',$this->adformat);
		$criteria->compare('width_height',$this->width_height,true);
		$criteria->compare('pv',$this->pv,true);
		$criteria->compare('impression',$this->impression,true);
		$criteria->compare('impression_ten_sec',$this->impression_ten_sec,true);
		$criteria->compare('click',$this->click,true);
		$criteria->compare('media_cost',$this->media_cost,true);
		$criteria->compare('media_tax_cost',$this->media_tax_cost,true);
		$criteria->compare('media_ops_cost',$this->media_ops_cost,true);
		$criteria->compare('income',$this->income,true);
		$criteria->compare('income_ten_sec',$this->income_ten_sec,true);
		$criteria->compare('agency_income',$this->agency_income,true);
		$criteria->compare('is_outside_tracking',$this->is_outside_tracking);
		$criteria->compare('sync_time',$this->sync_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function supplierDailyReport($tos_id)
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
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			t.settled_time as settled_time
		';

		$criteria->addInCondition("ad_space_id",$adSpacArray);
		$criteria->group = "settled_time";

		return new CActiveDataProvider($this, array(
			'pagination' => array(
				'pageSize' => 50
			),
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BuyReportDailyPc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
