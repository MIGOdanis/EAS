<?php
class EveTestYtbLogs extends CActiveRecord
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
            self::$conection = Yii::app()->eveTest;
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
		return '{{log}}';
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
			$criteria->addCondition("starttime >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-1 day'))));
			$criteria->addCondition("starttime <= " . strtotime(date("Y-m-d 23:59:59",strtotime('-1 day'))));
		}

		if($_GET['type'] == "7day"){
			$criteria->addCondition("starttime >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-7 day'))));
		}

		if($_GET['type'] == "30day"){
			$criteria->addCondition("starttime >= " . strtotime(date("Y-m-d 00:00:00",strtotime('-30 day'))));
		}

		if($_GET['type'] == "pastMonth"){
			$criteria->addCondition("starttime >= " . strtotime(date("Y-m-01 00:00:00",strtotime("-1 Months"))));
			$criteria->addCondition("starttime <= " . strtotime(date("Y-m-t 23:59:59",strtotime("-1 Months"))));
		}

		if($_GET['type'] == "thisMonth"){
			$criteria->addCondition("starttime >= " . strtotime(date("Y-m-01 00:00:00")));
			$criteria->addCondition("starttime <= " . strtotime(date("Y-m-t 23:59:59")));
		}	

		if($_GET['type'] == "custom"){
			if(isset($_GET['startDay']) && !empty($_GET['startDay'])){
				$criteria->addCondition("starttime >= " . strtotime($_GET['startDay'] . "00:00:00"));
			}
			if(isset($_GET['endDay']) &&  !empty($_GET['endDay'])){
				$criteria->addCondition("starttime <= " . strtotime($_GET['endDay'] . "23:59:59"));
			}			

		}

		return $criteria;
	}

	public function ytbCategoryReport($criteria)
	{
		$criteria = $this->addReportTime($criteria);
		$report = $this->findAll($criteria);

		$rawData = array();

		foreach ($report as $key => $value) {
			$sp = explode(":", $value->queryStr);
			$ratings = $this->ratings($value->play_duration, $value->video_duration);

			$criteria=new CDbCriteria;
			$criteria->addCondition("t.tos_id = '" . $sp[1] . "'");				
			$siteCategory = AdSpace::model()->with("site")->find($criteria);

			$rawData[$siteCategory->site->category->category_id]["totView"]++;
			$rawData[$siteCategory->site->category->category_id][$ratings]++;
		}			
		
		return $rawData;
		
	}

	public function ytbReport($criteria)
	{
		$criteria = $this->addReportTime($criteria);
		$report = $this->findAll($criteria);

		$rawData = array();



		if(isset($_GET['export']) && $_GET['export'] == 1){
			foreach ($report as $key => $value) {
				$sp = explode(":", $value->queryStr);
				$ratings = $this->ratings($value->play_duration, $value->video_duration);
				$rawData[date("Y-m-d",$value->starttime)]["totView"]++;
				$rawData[date("Y-m-d",$value->starttime)][$ratings]++;
			}			
			$rawData = $this->transInfoByCountDay($rawData);
		}else{
			foreach ($report as $key => $value) {
				$sp = explode(":", $value->queryStr);
				$ratings = $this->ratings($value->play_duration, $value->video_duration);
				$rawData[date("Y-m-d",$value->starttime)][$sp[1]][$sp[2]][$sp[3]]["totView"]++;
				$rawData[date("Y-m-d",$value->starttime)][$sp[1]][$sp[2]][$sp[3]][$ratings]++;
			}			
			$rawData = $this->transInfoByCount($rawData);	
		}
		
		return $rawData;
		
	}

	function transInfoByCountDay($rawData){
		$data = array();
		foreach ($rawData as $date => $dateValue) {
			$data[$date] = array(
				"totView" => $dateValue["totView"],
				"0" => (int)$dateValue["0"],
				"25" => (int)$dateValue["25"],
				"50" => (int)$dateValue["50"],							
				"75" => (int)$dateValue["75"],
				"100" => (int)$dateValue["100"],
			);	
		}

		return $data;

	}

	function transInfoByCount($rawData){
		$data = array();
		foreach ($rawData as $date => $dateValue) {
			foreach ($dateValue as $adspace => $adspaceValue) {
				$criteria=new CDbCriteria;
				$criteria->addCondition("t.tos_id = '" . $adspace . "'");
				$adspace = AdSpace::model()->find($criteria);
				foreach ($adspaceValue as $strategy => $strategyValue) {
					$criteria=new CDbCriteria;
					$criteria->addCondition("t.tos_id = '" . $strategy . "'");
					$strategy = Strategy::model()->find($criteria);		
	
					foreach ($strategyValue as $creative => $creativeValue) {
						$criteria=new CDbCriteria;
						$criteria->addCondition("t.tos_id = '" . $creative . "'");
						$creative = CreativeMaterial::model()->find($criteria);

						$data[$date][$strategy->tos_id][$creative->tos_id][$adspace->site->category->mediaCategory->id] = array(
							"totView" => $creativeValue["totView"],
							"0" => (int)$creativeValue["0"],
							"25" => (int)$creativeValue["25"],
							"50" => (int)$creativeValue["50"],							
							"75" => (int)$creativeValue["75"],
							"100" => (int)$creativeValue["100"],
							"adspace" => $adspace->name,
							"adspaceId" => $adspace->tos_id,
							"strategy" => $strategy->strategy_name,
							"strategyId" => $strategy->tos_id,
							"creative" =>  $creative->creativeGroup->name,
							"creativeId" =>  $creative->tos_id,
							"siteCategory"=> $adspace->site->category->mediaCategory->name
						);	
					}							
													
				}				
			}
		}

		return $data;

	}

	function ratings($play_duration, $video_duration){
		$video_duration = $video_duration-1;
		$ratings = ($play_duration / $video_duration) * 100;
		if($ratings > 25){
			if($ratings < 49){
				return 25;
			}
			if($ratings < 74){
				return 50;
			}
			if($ratings < 99){
				return 75;
			}
			if($ratings = 100){
				return 100;		
			}
		}
		return 0;
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
