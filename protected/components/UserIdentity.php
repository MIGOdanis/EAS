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
			$this->writeLog(
				"LOGIN AUTH Error: ERROR_USERNAME_INVALID FROM " . $this->username,
				"auth/login",
				date("Ymd") . "error.log"
			);			
		} elseif (!$User->validatePassword($this->password)) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
			$this->writeLog(
				"LOGIN AUTH Error: ERROR_PASSWORD_INVALID  FROM " . $this->username,
				"auth/login",
				date("Ymd") . "error.log"
			);			
		} else {
			$User->last_login = time();
			$User->save();

			$this->writeLog(
				"LOGIN AUTH : " . $User->id,
				"auth/login",
				date("Ymd") . ".log"
			);

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

	//log
	public function writeLog($str,$dir,$fileName){
		if (!is_dir(dirname(__FILE__) . "/../../logs/" . $dir)){     //檢察upload資料夾是否存在
			mkdir(dirname(__FILE__) . "/../../logs/" . $dir, 0755, true);
		}
		$path = dirname(__FILE__) . "/../../logs/" . $dir . "/" .$fileName;
		$type = (is_file($path)) ? "a+" : "w+";
		$file = fopen($path,$type);
		$content = date("Y-m-d H:i:s") . " | " . $str . "\r\n";
	    fwrite($file,$content);
	    fclose($file);	
	}

}