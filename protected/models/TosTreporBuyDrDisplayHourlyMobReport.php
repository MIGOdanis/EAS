<?php
class TosTreporBuyDrDisplayHourlyMobReport extends CActiveRecord
{
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
            self::$conection = Yii::app()->treport;
            if (self::$conection instanceof CDbConnection)
            {
                self::$conection->setActive(true);
                return self::$conection;
            }
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "TosCore" CDbConnection application component.'));
        }
    }

	public function tableName()
	{
		return '{{buy_dr_display_hourly_mob_report_nct}}';
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
			'adSpace' => array(self::HAS_ONE, 'TosCoreAdSpace', array('id' => 'ad_space_id')),
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

	public function addNoPayCampaign($criteria){

		if($_GET['showNoPay'] != "all"){
			$noPayCriteria = new CDbCriteria;
			$noPayCriteria->addInCondition("advertiser_id",Yii::app()->params['noPayAdvertiser']);		
			$noPayCampaign = Campaign::model()->findAll($noPayCriteria);
			$noPayCampaignId = array();

			foreach ($noPayCampaign as $value) {
				$noPayCampaignId[] = $value->tos_id;
			}

			if(isset($_GET['showNoPay']) && $_GET['showNoPay'] == "only"){
				 $criteria->addInCondition("campaign_id",$noPayCampaignId);
			}else{
				$criteria->addNotInCondition("campaign_id",$noPayCampaignId);
			}
		}
		
		return $criteria;
	}

	public function addReportTime($criteria, $columns = "settled_time")
	{
		
		if($_GET['type'] == "custom"){
			if(isset($_GET['day']) && !empty($_GET['day'])){
				$criteria->addCondition($columns ." >= '" . date("Y-m-d 00:00:00",strtotime($_GET['day'])) . "'");
				$criteria->addCondition($columns ." <= '" . date("Y-m-d 23:59:59",strtotime($_GET['day'])) . "'");
			}
		}else{
				$criteria->addCondition($columns ." >= '" . date("Y-m-d 00:00:00") . "'");
				$criteria->addCondition($columns ." <= '" . date("Y-m-d 23:59:59") . "'");			
		}

		// print_r($criteria); exit;
		return $criteria;
	}

	public function getAdSpace()
	{
		if($_GET['supplierId'] > 0 || $_GET['siteId'] > 0 || $_GET['adSpaceId'] > 0){

			$criteria=new CDbCriteria;

			if($_GET['supplierId'] > 0){
				$criteria->addCondition("t.tos_id = '" . $_GET['supplierId'] ."'");
			}
			
			if($_GET['siteId'] > 0){
				$criteria->addCondition("site.tos_id = '" . $_GET['siteId'] . "'");
			}

			if($_GET['adSpaceId'] > 0){
				$criteria->addCondition("adSpace.tos_id = '" . $_GET['adSpaceId'] . "'");
			}		

			$supplier = Supplier::model()->with("site","site.adSpace")->find($criteria);
			$adSpacArray = array();	

			if($supplier !== null){
				foreach ($supplier->site as $site) {
					foreach ($site->adSpace as $value) {
						$adSpacArray[] = $value->tos_id;
					}
				}				
			}


			return $adSpacArray;			
		}
	}



	public function getHourlyByDay()
	{
		$criteria=new CDbCriteria;
		$criteria->select = '
			sum(t.media_cost) / 100000 as media_cost,
			sum(t.click) as click,
			sum(t.impression) as impression,
			sum(t.pv) as pv,
			settled_time
		';


		$criteria = $this->addReportTime($criteria);

		$adSpacArray = $this->getAdSpace();
		if(!empty($adSpacArray))
			$criteria->addInCondition("ad_space_id",$adSpacArray);
		
		$criteria = $this->addNoPayCampaign($criteria);
		$criteria->group = "settled_time";

		// print_r($this->findAll($criteria)); exit;
		return $this->findAll($criteria);	
	}	
}
