<?php
class User extends CActiveRecord
{
	public $repeat_password;
	public $new_password;	

	/**
	 * @return string the associated database table name
	 */

	public function getdb()
	{
		return $this->getDbConnection();
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
		return array(
			array('user, password, name, active, auth_id, group, creat_time, repeat_password', 'required', 'on'=>'register'),
			array('user, password, name, auth_id, group, creat_time, active', 'required'),
			array('active, creat_time,supplier_id', 'numerical', 'integerOnly'=>true),
			array('name, password, name', 'length', 'max'=>255),
			array('id, name, password, name, active, creat_time,supplier_id', 'safe', 'on'=>'search'),
			array('user', 'unique', 'message'=>'{attribute}"{value}"已經使用', 'on'=>'register'),
			array('name', 'unique', 'message'=>'{attribute}"{value}"已經使用', 'on'=>'register'),
			array('new_password', 'compare', 'compareAttribute'=>'repeat_password', 'on'=>'repassword', 'message'=>'新{attribute}與再次輸入的密碼不同'),
			array('password, new_password, repeat_password', 'required', 'on'=>'repassword'),
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
			'auth' => array(self::HAS_ONE, 'AuthGroup', array('id' => 'auth_id')),
			'supplier' => array(self::HAS_ONE, 'Supplier', array('id' => 'supplier_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user' => '帳號',
			'password' => '密碼',
			'new_password' => '新密碼',
			'repeat_password' => '確認密碼',						
			'name' => '名稱',
			'auth_id' => '權限',
			'group' => '群組',
			'creat_time' => '建立時間',
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
		$criteria->compare('user',$this->user,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('auth_id',$this->auth_id);
		$criteria->compare('group',$this->group);
		$criteria->compare('creat_time',$this->creat_time);
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

	public function getUserBySupplier($supplier_id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('auth_id',$this->auth_id);
		$criteria->compare('group',$this->group);
		$criteria->compare('creat_time',$this->creat_time);
		$criteria->compare('active',$this->active);

		$criteria->addCondition("supplier_id = " . (int)$supplier_id);

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
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		// print_r(crypt($password,$this->password));
		// echo "<br>";
		// print_r($this->password);
		// exit;
		return crypt($password,$this->password)===$this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @return string hash
	 */
	public function hashPassword($password)
	{
		return crypt($password, $this->generateSalt());
	}

	/**
	 * Generates a salt that can be used to generate a password hash.
	 *
	 * The {@link http://php.net/manual/en/function.crypt.php PHP `crypt()` built-in function}
	 * requires, for the Blowfish hash algorithm, a salt string in a specific format:
	 *  - "$2a$"
	 *  - a two digit cost parameter
	 *  - "$"
	 *  - 22 characters from the alphabet "./0-9A-Za-z".
	 *
	 * @param int cost parameter for Blowfish hash algorithm
	 * @return string the salt
	 */
	protected function generateSalt($cost=10)
	{
		if(!is_numeric($cost)||$cost<4||$cost>31){
			throw new CException(Yii::t('Cost parameter must be between 4 and 31.'));
		}
		// Get some pseudo-random data from mt_rand().
		$rand='';
		for($i=0;$i<8;++$i)
			$rand.=pack('S',mt_rand(0,0xffff));
		// Add the microtime for a little more entropy.
		$rand.=microtime();
		// Mix the bits cryptographically.
		$rand=sha1($rand,true);
		// Form the prefix that specifies hash algorithm type and cost parameter.
		$salt='$2a$'.str_pad((int)$cost,2,'0',STR_PAD_RIGHT).'$';
		// Append the random salt string in the required base64 format.
		$salt.=strtr(substr(base64_encode($rand),0,22),array('+'=>'.'));
		return $salt;
	}
    
	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new UserIdentity($this->name,$this->current_password);
		if(!$this->_identity->authenticate())
			$this->addError('current_password','原密碼輸入錯誤');
	} 	

}
