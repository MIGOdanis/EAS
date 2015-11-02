<?php
class UIAuthWidget extends CWidget
{
	public $checkType; //group or auth
	public $auth;
	public $user;
	public $html;
	private $_check;

	public function init()
	{
		$this->_check = 0;
		$ua = json_decode($this->user->auth->auth,true);

		//驗證USER權限組
		if($this->checkType == "auth"){
			foreach ($this->auth as $value) {
				$auth = explode("/", $value);
				$authGroup = $ua[$auth[0]][$auth[1]];
				if(is_array($authGroup)){
					if(in_array($auth[2], $authGroup)){
						$this->_check = 1;
					}
				}
			}
	
		}		

		//GROUP採反向驗證 在名單內不顯示
		if($this->checkType == "group"){
			$this->_check = 1;
			if(in_array($this->user->group, $this->auth)){
				$this->_check = 0;
			}
		}
	
	}
	
	public function run()
	{   
		$this->render('uIAuthWidget', array("html" => $this->html, "check" => $this->_check));
	}
}
