<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 * ------------------
 * 修改欄位名稱
 *
 * @author KeaNy 
 * @date 2013.11.13
 * @spend 1 min 
 */
class LoginForm extends CFormModel
{
	public $user;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that user and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// user and password are required
			array('user, password', 'required', 'message'=>'請填入{attribute}'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'user'=>'電子信箱',
			'password'=>'密碼',
			'rememberMe'=>'記住我',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		$this->_identity=new UserIdentity($this->user,$this->password);
		if(!$this->_identity->authenticate()){
			if($this->_identity->errorCode === UserIdentity::ERROR_USERNAME_INACTIVE)
				$this->addError('user','帳號已停用。');
			else
				$this->addError('password','錯誤的帳號或密碼。');
		}
	}

	/**
	 * Logs in the user using the given user and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->user,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
