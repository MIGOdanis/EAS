<?php
class EveDspLogsDspTosFunc extends CActiveRecord
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
            self::$conection = Yii::app()->dspAlert;
            if (self::$conection instanceof CDbConnection)
            {
                self::$conection->setActive(true);
                return self::$conection;
            }
            else
                throw new CDbException(Yii::t('yii','Active Record requires a "Events" CDbConnection application component.'));
        }
    }

	public function tableName()
	{
		return '{{tosfunc}}';
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

	public function addReportTime($criteria)
	{
		if(!isset($_GET) || $_GET['type'] == "yesterday"){
			$criteria->addCondition("creat_time >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-1 day'))));
			$criteria->addCondition("creat_time <= " . strtotime(date("Y-m-d 23:59:59",strtotime('-1 day'))));
		}

		if($_GET['type'] == "7day"){
			$criteria->addCondition("creat_time >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-7 day'))));
		}

		if($_GET['type'] == "30day"){
			$criteria->addCondition("creat_time >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-30 day'))));
		}

		if($_GET['type'] == "pastMonth"){
			$criteria->addCondition("creat_time >= " . strtotime(date("Y-m-01 00:00:00",strtotime("-1 Months"))));
			$criteria->addCondition("creat_time <= " . strtotime(date("Y-m-t 23:59:59",strtotime("-1 Months"))));
		}

		if($_GET['type'] == "thisMonth"){
			$criteria->addCondition("creat_time >= " . strtotime(date("Y-m-01 00:00:00")));
			$criteria->addCondition("creat_time <= " . strtotime(date("Y-m-t 23:59:59")));
		}	

		if($_GET['type'] == "custom"){
			if(isset($_GET['startDay']) && !empty($_GET['startDay'])){
				$criteria->addCondition("creat_time >= " . strtotime($_GET['startDay'] . "00:00:00"));
			}
			if(isset($_GET['endDay']) &&  !empty($_GET['endDay'])){
				$criteria->addCondition("creat_time <= " . strtotime($_GET['endDay'] . "23:59:59"));
			}			

		}

		return $criteria;
	}

	public function funcReport($criteria)
	{
		$criteria = $this->addReportTime($criteria);
		$report = $this->findAll($criteria);
		
		$countFunction = array();
		$functionName =array();

		if(isset($_GET['export']) && $_GET['export'] == 1){
			foreach ($report as $key => $value) {
				$countFunction[date("Y-m-d",$value->creat_time)]++;
				$functionName[date("Y-m-d",$value->creat_time)] = $value->func;
			}

			$rawData = $this->transInfoByCountDay($countFunction,$functionName);
		}else{
			foreach ($report as $key => $value) {
				$countFunction[date("Y-m-d",$value->creat_time)][$value->creative]++;
				$functionName[date("Y-m-d",$value->creat_time)][$value->creative] = $value->func;
			}

			$rawData = $this->transInfoByCount($countFunction,$functionName);
		}

		return $rawData;
		
	}

	function transInfoByCountDay($countFunction,$functionName){
		$data = array();
		foreach ($countFunction as $date => $dateValue) {
			$data[$date] = array(
				"totClick" => (int)$click,
				"functionName" => $functionName[$date],
			);				
		}

		return $data;

	}

	function transInfoByCount($countFunction,$functionName){
		$data = array();
		foreach ($countFunction as $date => $dateValue) {
			foreach ($dateValue as $creative => $click) {
				$data[$date][$creative] = array(
					"totClick" => (int)$click,
					"functionName" => $functionName[$date][$creative],
				);				
			}
		}

		return $data;

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
