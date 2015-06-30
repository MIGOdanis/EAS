<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	const ERROR_USERNAME_INACTIVE=3;
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{

		$User=User::model()->find('LOWER(user)=? AND active=1',array(strtolower($this->username)));
		//print_r($User); exit;
		if ($User===null) {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		} elseif (!$User->validatePassword($this->password)) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		} else {
			$this->_id=$User->id;
			$this->username=$User->user;
			$this->setState('name', $User->name);
			$this->setState('user', $User->user);
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}


	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
}