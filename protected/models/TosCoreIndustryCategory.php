<?php
class TosCoreIndustryCategory extends CActiveRecord
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
            self::$conection = Yii::app()->core;
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
		return '{{industry_category_zh_tw}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

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


	public function elandFunction($id){
		$elandCat = array(
			"10" => "化妝保養品",
			"11" => "家用品",
			"12" => "食品",
			"13" => "菸酒",
			"14" => "服飾",
			"15" => "通路",
			"16" => "服飾",
			"17" => "金融財經",
			"18" => "建築",
			"19" => "交通工具",
			"20" => "交通工具",
			"21" => "家用品",
			"22" => "電信",
			"23" => "醫藥美容",
			"24" => "通路",
			"25" => "教育",
			"26" => "其他",
			"28" => "其他",
			"29" => "其他",
			"30" => "其他",
			"31" => "娛樂",
			"32" => "其他",
			"33" => "其他",
			"34" => "其他",
			"35" => "其他",
			"36" => "其他",
			"37" => "其他",
			"1001" => "化妝保養品",
			"1002" => "化妝保養品",
			"1003" => "化妝保養品",
			"1004" => "化妝保養品",
			"1005" => "化妝保養品",
			"1006" => "化妝保養品",
			"1007" => "化妝保養品",
			"1008" => "化妝保養品",
			"1009" => "化妝保養品",
			"1010" => "化妝保養品",
			"1011" => "化妝保養品",
			"1012" => "醫藥美容",
			"1013" => "其他",
			"1101" => "家用品",
			"1102" => "家用品",
			"1103" => "家用品",
			"1104" => "家用品",
			"1105" => "家用品",
			"1106" => "家用品",
			"1201" => "飲料",
			"1202" => "飲料",
			"1203" => "飲料",
			"1204" => "飲料",
			"1205" => "飲料",
			"1206" => "飲料",
			"1207" => "食品",
			"1208" => "食品",
			"1209" => "食品",
			"1210" => "食品",
			"1211" => "食品",
			"1212" => "食品",
			"1213" => "食品",
			"1214" => "食品",
			"1215" => "食品",
			"1216" => "食品",
			"1217" => "飲料",
			"1301" => "菸酒",
			"1401" => "服飾",
			"1402" => "服飾",
			"1403" => "服飾",
			"1404" => "服飾",
			"1405" => "服飾",
			"1501" => "通路",
			"1502" => "通路",
			"1503" => "通路",
			"1504" => "通路",
			"1505" => "通路",
			"1506" => "通路",
			"1507" => "通路",
			"1601" => "服飾",
			"1602" => "服飾",
			"1603" => "服飾",
			"1604" => "服飾",
			"1605" => "服飾",
			"1606" => "服飾",
			"1607" => "服飾",
			"1608" => "服飾",
			"1701" => "金融財經",
			"1702" => "金融財經",
			"1703" => "金融財經",
			"1704" => "金融財經",
			"1705" => "金融財經",
			"1801" => "建築",
			"1802" => "建築",
			"1803" => "建築",
			"1804" => "建築",
			"1901" => "交通工具",
			"1902" => "交通工具",
			"1903" => "交通工具",
			"1904" => "交通工具",
			"1905" => "交通工具",
			"1906" => "交通工具",
			"1907" => "交通工具",
			"1908" => "交通工具",
			"1909" => "交通工具",
			"1910" => "交通工具",
			"1911" => "交通工具",
			"1912" => "交通工具",
			"2001" => "交通工具",
			"2002" => "交通工具",
			"2003" => "交通工具",
			"2004" => "交通工具",
			"2005" => "交通工具",
			"2006" => "交通工具",
			"2101" => "家用品",
			"2102" => "家用品",
			"2103" => "家用品",
			"2104" => "家用品",
			"2105" => "家用品",
			"2106" => "家用品",
			"2201" => "電信",
			"2202" => "電信",
			"2203" => "電信",
			"2204" => "電信",
			"2205" => "電信",
			"2301" => "醫藥美容",
			"2302" => "醫藥美容",
			"2303" => "醫藥美容",
			"2401" => "通路",
			"2402" => "通路",
			"2403" => "通路",
			"2404" => "通路",
			"2501" => "教育",
			"2502" => "教育",
			"2503" => "教育",
			"2504" => "教育",
			"2601" => "其他",
			"2602" => "其他",
			"2603" => "其他",
			"2604" => "其他",
			"2802" => "其他",
			"2803" => "其他",
			"2804" => "其他",
			"2805" => "其他",
			"3001" => "其他",
			"3002" => "其他",
			"3003" => "其他",
			"3004" => "其他",
			"3005" => "其他",
			"3006" => "其他",
			"3007" => "其他",
			"3008" => "其他",
			"3009" => "其他",
			"3010" => "其他",
			"3011" => "其他",
			"3012" => "其他",
			"3013" => "其他",
			"3014" => "其他",
			"3015" => "其他",
			"3016" => "其他",
			"3101" => "娛樂",
			"3102" => "娛樂",
			"3103" => "娛樂",
			"3104" => "娛樂",
			"3105" => "娛樂",
			"3106" => "娛樂",
			"3107" => "娛樂",
			"3108" => "娛樂",
			"3109" => "娛樂",
			"3110" => "娛樂",
			"3111" => "娛樂",
			"3112" => "娛樂",
			"3113" => "娛樂",
			"3114" => "娛樂",
			"3115" => "娛樂",
			"3116" => "娛樂",
			"3117" => "娛樂",
			"3118" => "娛樂",
			"3119" => "娛樂",
			"3201" => "其他",
			"3202" => "其他",
			"3203" => "其他",
			"3204" => "其他",
			"3205" => "其他",
			"3206" => "其他",
			"3207" => "其他",
			"3208" => "其他",
			"3209" => "其他",
			"3210" => "其他",
			"3211" => "其他",
			"3212" => "其他",
			"3213" => "其他",
			"3214" => "其他",
			"3215" => "其他",
			"3216" => "其他",
			"3217" => "其他",
			"3218" => "其他",
			"3219" => "其他",
			"3220" => "其他",
			"3221" => "其他",
			"3222" => "其他",
			"3223" => "其他",
			"3224" => "其他",
			"100101" => "化妝保養品",
			"100102" => "化妝保養品",
			"100103" => "化妝保養品",
			"100104" => "化妝保養品",
			"100105" => "化妝保養品",
			"100106" => "化妝保養品",
			"100107" => "化妝保養品",
			"100108" => "化妝保養品",
			"100109" => "化妝保養品",
			"100201" => "化妝保養品",
			"100202" => "化妝保養品",
			"100203" => "化妝保養品",
			"100204" => "化妝保養品",
			"100205" => "化妝保養品",
			"100206" => "化妝保養品",
			"100207" => "化妝保養品",
			"100208" => "化妝保養品",
			"100301" => "化妝保養品",
			"100302" => "化妝保養品",
			"100303" => "化妝保養品",
			"100304" => "化妝保養品",
			"100305" => "化妝保養品",
			"100306" => "化妝保養品",
			"100307" => "化妝保養品",
			"100308" => "化妝保養品",
			"100309" => "化妝保養品",
			"100401" => "化妝保養品",
			"100402" => "化妝保養品",
			"100403" => "化妝保養品",
			"100404" => "化妝保養品",
			"100501" => "化妝保養品",
			"100502" => "化妝保養品",
			"100503" => "化妝保養品",
			"100504" => "化妝保養品",
			"100505" => "化妝保養品",
			"100601" => "化妝保養品",
			"100602" => "化妝保養品",
			"100603" => "化妝保養品",
			"100701" => "化妝保養品",
			"100702" => "化妝保養品",
			"100703" => "化妝保養品",
			"100704" => "化妝保養品",
			"100801" => "化妝保養品",
			"100802" => "化妝保養品",
			"100803" => "化妝保養品",
			"100804" => "化妝保養品",
			"100805" => "化妝保養品",
			"100901" => "化妝保養品",
			"100902" => "化妝保養品",
			"100903" => "化妝保養品",
			"101001" => "化妝保養品",
			"101002" => "化妝保養品",
			"101003" => "化妝保養品",
			"101004" => "化妝保養品",
			"101005" => "化妝保養品",
			"101006" => "化妝保養品",
			"101007" => "化妝保養品",
			"101008" => "化妝保養品",
			"101009" => "化妝保養品",
			"101101" => "化妝保養品",
			"101102" => "化妝保養品",
			"101103" => "化妝保養品",
			"101104" => "化妝保養品",
			"101105" => "化妝保養品",
			"101201" => "醫藥美容",
			"101202" => "醫藥美容",
			"101203" => "醫藥美容",
			"110101" => "家用品",
			"110102" => "家用品",
			"110103" => "家用品",
			"110104" => "家用品",
			"110105" => "家用品",
			"110106" => "家用品",
			"110107" => "家用品",
			"110201" => "家用品",
			"110202" => "家用品",
			"110203" => "家用品",
			"110301" => "家用品",
			"110302" => "家用品",
			"110303" => "家用品",
			"110401" => "家用品",
			"110402" => "家用品",
			"110403" => "家用品",
			"110404" => "家用品",
			"110405" => "家用品",
			"110406" => "家用品",
			"110501" => "家用品",
			"110502" => "家用品",
			"110503" => "家用品",
			"110504" => "家用品",
			"110505" => "家用品",
			"110506" => "家用品",
			"110507" => "家用品",
			"110508" => "家用品",
			"110601" => "家用品",
			"110602" => "家用品",
			"110603" => "家用品",
			"120101" => "飲料",
			"120102" => "飲料",
			"120103" => "飲料",
			"120104" => "飲料",
			"120201" => "飲料",
			"120202" => "飲料",
			"120203" => "飲料",
			"120204" => "飲料",
			"120205" => "飲料",
			"120206" => "飲料",
			"120301" => "菸酒",
			"120302" => "菸酒",
			"120303" => "菸酒",
			"120304" => "菸酒",
			"120305" => "菸酒",
			"120401" => "飲料",
			"120501" => "乳麥品",
			"120502" => "乳麥品",
			"120503" => "乳麥品",
			"120504" => "乳麥品",
			"120505" => "乳麥品",
			"120506" => "乳麥品",
			"120507" => "乳麥品",
			"120601" => "飲料",
			"120602" => "飲料",
			"120701" => "食品",
			"120702" => "食品",
			"120703" => "食品",
			"120704" => "食品",
			"120801" => "食品",
			"120802" => "食品",
			"120803" => "食品",
			"120804" => "食品",
			"120901" => "食品",
			"120902" => "食品",
			"120903" => "食品",
			"120904" => "食品",
			"120905" => "食品",
			"121001" => "食品",
			"121002" => "食品",
			"121003" => "食品",
			"121004" => "食品",
			"121601" => "乳麥品",
			"121602" => "食品",
			"121603" => "食品",
			"121604" => "食品",
			"121605" => "食品",
			"121606" => "菸酒",
			"150101" => "通路",
			"150102" => "醫藥美容",
			"150103" => "通路",
			"150104" => "通路",
			"150105" => "3C",
			"150106" => "服飾",
			"150107" => "通路",
			"150401" => "食品",
			"150402" => "食品",
			"150403" => "食品",
			"150501" => "娛樂",
			"150502" => "娛樂",
			"150503" => "娛樂",
			"150504" => "娛樂",
			"150601" => "其他服務類",
			"150602" => "其他服務類",
			"150603" => "其他服務類",
			"150604" => "其他服務類",
			"150605" => "其他服務類",
			"150606" => "其他服務類",
			"150607" => "其他服務類",
			"150608" => "其他服務類",
			"150609" => "其他服務類",
			"150610" => "其他服務類",
			"150611" => "其他服務類",
			"150612" => "其他服務類",
			"150613" => "其他服務類",
			"150614" => "其他服務類",
			"150615" => "其他服務類",
			"150616" => "其他服務類",
			"150617" => "其他服務類",
			"150701" => "其他服務類",
			"150702" => "其他服務類",
			"150703" => "其他服務類",
			"160401" => "服飾",
			"160402" => "服飾",
			"160403" => "服飾",
			"160404" => "服飾",
			"160601" => "服飾",
			"160602" => "服飾",
			"160603" => "服飾",
			"160604" => "服飾",
			"160701" => "服飾",
			"160702" => "服飾",
			"160703" => "服飾",
			"170101" => "金融財經",
			"170102" => "金融財經",
			"170103" => "金融財經",
			"170104" => "金融財經",
			"170105" => "金融財經",
			"170201" => "金融財經",
			"170202" => "金融財經",
			"170203" => "金融財經",
			"170204" => "金融財經",
			"170205" => "金融財經",
			"170206" => "金融財經",
			"170207" => "金融財經",
			"170208" => "金融財經",
			"170209" => "金融財經",
			"170210" => "金融財經",
			"170301" => "金融財經",
			"170302" => "金融財經",
			"170303" => "金融財經",
			"170501" => "金融財經",
			"170502" => "金融財經",
			"170503" => "金融財經",
			"170504" => "金融財經",
			"170506" => "金融財經",
			"170507" => "金融財經",
			"180101" => "建築",
			"180102" => "建築",
			"180103" => "建築",
			"180105" => "建築",
			"180106" => "建築",
			"180107" => "建築",
			"180108" => "建築",
			"180301" => "建築",
			"180302" => "建築",
			"180303" => "建築",
			"180304" => "建築",
			"180305" => "建築",
			"180306" => "建築",
			"180307" => "建築",
			"180308" => "建築",
			"190101" => "交通工具",
			"190102" => "交通工具",
			"190103" => "交通工具",
			"190104" => "交通工具",
			"190105" => "交通工具",
			"190106" => "交通工具",
			"190801" => "交通工具",
			"190802" => "交通工具",
			"190803" => "交通工具",
			"190804" => "交通工具",
			"190805" => "交通工具",
			"200101" => "旅遊",
			"200102" => "旅遊",
			"200103" => "旅遊",
			"200104" => "旅遊",
			"200201" => "交通工具",
			"200202" => "交通工具",
			"200203" => "交通工具",
			"200301" => "交通工具",
			"200302" => "交通工具",
			"200401" => "交通工具",
			"200402" => "交通工具",
			"200403" => "交通工具",
			"210101" => "家用品",
			"210102" => "家用品",
			"210103" => "家用品",
			"210104" => "家用品",
			"210105" => "家用品",
			"210106" => "家用品",
			"210107" => "家用品",
			"210108" => "3C",
			"210201" => "3C",
			"210202" => "3C",
			"210203" => "3C",
			"210204" => "3C",
			"210205" => "3C",
			"210206" => "3C",
			"210207" => "3C",
			"210208" => "3C",
			"210209" => "3C",
			"210301" => "家電",
			"210302" => "家電",
			"210303" => "家電",
			"210401" => "家電",
			"210402" => "家電",
			"210403" => "家電",
			"210404" => "家電",
			"210501" => "家電",
			"210502" => "家電",
			"210503" => "家電",
			"210504" => "家電",
			"210505" => "家電",
			"210506" => "家電",
			"220101" => "電信",
			"220102" => "電信",
			"220103" => "電信",
			"220201" => "電信",
			"220202" => "電信",
			"220203" => "電信",
			"220204" => "電信",
			"220205" => "電信",
			"220206" => "電信",
			"220301" => "電信",
			"220302" => "電信",
			"220303" => "電信",
			"220304" => "電信",
			"220305" => "電信",
			"220401" => "電信",
			"220402" => "電信",
			"220403" => "電信",
			"220404" => "電信",
			"220405" => "電信",
			"220406" => "電信",
			"220407" => "電信",
			"220408" => "電信",
			"230101" => "醫藥美容",
			"230102" => "醫藥美容",
			"230103" => "醫藥美容",
			"230201" => "醫藥美容",
			"230202" => "醫藥美容",
			"230203" => "醫藥美容",
			"230204" => "醫藥美容",
			"230205" => "醫藥美容",
			"250101" => "教育",
			"250102" => "教育",
			"250103" => "教育",
			"250104" => "教育",
			"250105" => "教育",
			"250106" => "教育",
			"250107" => "教育",
			"250108" => "教育",
			"250109" => "教育",
			"250110" => "教育",
			"250111" => "教育",
			"250112" => "教育",
			"250201" => "教育",
			"250202" => "教育",
			"250203" => "教育",
			"250204" => "教育",
			"250205" => "教育",
			"250206" => "教育",
			"250207" => "教育",
			"250208" => "教育",
			"250209" => "教育",
			"250210" => "教育",
			"250211" => "教育",
			"250212" => "教育",
			"280101" => "其他",
			"280102" => "其他",
			"280103" => "其他",
			"280104" => "其他",
			"280105" => "其他",
			"280106" => "其他",
			"280107" => "其他",
			"280108" => "其他",
			"280110" => "其他",
			"280111" => "其他",
			"320101" => "其他",
			"320102" => "其他",
			"320103" => "其他",
			"320104" => "其他",
			"320105" => "其他",
			"320106" => "其他",
			"320107" => "其他",
			"320108" => "其他",
			"320801" => "其他",
			"320802" => "其他",
			"320803" => "其他",
			"320804" => "其他",
			"320805" => "其他",
			"320806" => "其他",
			"321001" => "其他",
			"321002" => "其他",
			"321003" => "其他",
			"321004" => "其他",
			"321005" => "其他",
			"321006" => "其他",
			"321201" => "其他",
			"321202" => "其他",
			"321203" => "其他",
			"321204" => "其他",
			"321205" => "其他",
			"321206" => "其他",
			"321801" => "其他",
			"321802" => "其他",
			"321803" => "其他",
			"321804" => "其他",
			"321901" => "其他",
			"321902" => "其他",
			"321903" => "其他",
			"321904" => "其他",
			"321905" => "其他",
			"322001" => "其他",
			"322002" => "其他",
			"322003" => "其他",
			"322004" => "其他",
			"322005" => "其他",
			"322101" => "其他",
			"322102" => "其他",
			"322103" => "其他",
			"322104" => "其他",
			"322105" => "其他",
			"322201" => "其他",
			"322202" => "其他",
			"322203" => "其他",
			"322204" => "其他",
			"322205" => "其他",
			"10110401" => "醫藥美容",
			"10110402" => "醫藥美容",
			"10110403" => "醫藥美容",
			"10110404" => "醫藥美容",
			"10110405" => "醫藥美容",
			"10110406" => "醫藥美容",
			"10110407" => "醫藥美容",
			"10110408" => "醫藥美容",
			"10110409" => "醫藥美容",
			"10110410" => "醫藥美容",
			"10110411" => "醫藥美容",
			"10110412" => "醫藥美容",
			"11030101" => "家用品",
			"11030102" => "家用品",
			"11030103" => "家用品",
			"11030104" => "家用品",
			"11030105" => "家用品",
			"11030106" => "家用品",
			"11030107" => "家用品",
			"11030108" => "家用品",
			"11040501" => "家用品",
			"11040502" => "家用品",
			"11040503" => "家用品",
			"11040504" => "家用品",
			"11040505" => "家用品",
			"11040506" => "家用品",
			"11040507" => "家用品",
			"12030201" => "菸酒",
			"12030202" => "菸酒",
			"12030203" => "菸酒",
			"12030401" => "菸酒",
			"12030402" => "菸酒",
			"12030403" => "菸酒",
			"12030404" => "菸酒",
			"12030405" => "菸酒",
			"12030406" => "菸酒",
			"12030407" => "菸酒",
			"15060601" => "其他服務類",
			"15060602" => "其他服務類",
			"15060603" => "其他服務類",
			"16070101" => "服飾",
			"16070102" => "服飾",
			"16070103" => "服飾",
			"16070104" => "服飾",
			"16070105" => "服飾",
			"16070201" => "服飾",
			"16070202" => "服飾",
			"16070203" => "服飾",
			"16070204" => "服飾",
			"16070301" => "服飾",
			"16070302" => "服飾",
			"16070303" => "服飾",
			"16070304" => "服飾",
			"16070305" => "服飾",
			"16070306" => "服飾",
			"16070307" => "服飾",
			"16070308" => "服飾",
			"16070309" => "服飾",
			"16070310" => "服飾",
			"16070311" => "服飾",
			"17030201" => "金融財經",
			"17030202" => "金融財經",
			"17030203" => "金融財經",
			"17030204" => "金融財經",
			"17030205" => "金融財經",
			"17030206" => "金融財經",
			"19010101" => "交通工具",
			"19010102" => "交通工具",
			"19010103" => "交通工具",
			"19010104" => "交通工具",
			"19010105" => "交通工具",
			"20010201" => "旅遊",
			"20010202" => "旅遊",
			"20010203" => "旅遊",
			"20010204" => "旅遊",
			"20010205" => "旅遊",
			"20010206" => "旅遊",
			"20010207" => "旅遊",
			"21020101" => "3C",
			"21020102" => "3C",
			"21020201" => "3C",
			"21020202" => "3C",
			"21020203" => "3C",
			"21020204" => "3C",
			"21020205" => "3C",
			"21020206" => "3C",
			"21020301" => "3C",
			"21020302" => "3C",
			"22010101" => "電信",
			"22010102" => "電信",
			"22010103" => "電信",
			"22010104" => "電信",
			"22010105" => "電信",
			"22010106" => "電信",
			"22010107" => "電信",
			"22010108" => "電信",
			"22010109" => "電信",
			"22010110" => "電信",
			"22010111" => "電信",
			"22010112" => "電信",
			"22010201" => "電信",
			"22010202" => "電信",
			"22010203" => "電信",
			"22010204" => "電信",
			"23020301" => "運動",
			"23020302" => "運動",
			"23020303" => "運動",
			"23020304" => "運動",
			"23020305" => "運動",
			"23020401" => "運動",
			"23020402" => "運動",
			"23020403" => "運動",
			"23020404" => "運動",
			"23020405" => "運動",
			"23020406" => "運動",
			"23020407" => "運動",
			"23020408" => "運動",
			"23020409" => "運動",
			"23020410" => "運動",
			"23020411" => "運動",
			"23020412" => "運動",
			"23020413" => "運動",
			"23020414" => "運動",
			"23020415" => "運動",
			"23020416" => "運動",
			"23020417" => "運動",
			"23020418" => "運動",
			"23020419" => "運動",
			"23020420" => "運動",
			"23020421" => "運動",
			"23020422" => "運動",
			"23020423" => "運動",
			"23020424" => "運動",
			"23020425" => "運動",
			"23020426" => "運動",
			"23020427" => "運動",
			"23020428" => "運動",
			"23020429" => "運動",
			"23020430" => "運動",
			"23020431" => "運動",
			"32010801" => "其他",
			"32010802" => "其他",
			"32010803" => "其他"
		);
		
		return $elandCat[$id];
	}
}
