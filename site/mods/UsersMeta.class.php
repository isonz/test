<?php
class UsersMeta extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_users_meta';
	
	static function addData($username, $phone=null, $password=null, $orle=1)
	{
		if(!$username) return false;
		if(!self::regSendEmail($username)) return false;
		
		$condition = array(
			"username"	 	=> $username,
		);
		if(!self::check(array("condition"=>$condition))){
			$salt = Func::getRandomCode(10);
			$password = md5(md5($password).$salt);
			$data = array(
				"username"		=> $username,
				"passwd"		=> $password,
				"salt"			=> $salt,
				"phone"			=> $phone,
				"role"			=> $orle,
				"created_at"	=> Func::getTime(),					
			);
			return self::insert($data);
		}
		return false;
	}

	static function editData($username, array $data)
	{
		if(!$username) return false;
		$condition = array(
			"username"	 => $username,
		);
		if(self::check(array("condition"=>$condition))){
			return self::update($condition, $data);
		}
		return false;
	}
	
	static function getInfoByName($name, $select='*')
	{
		$info = self::getOne(array("username" => $name), $select);
		return $info;
	}
	
	
}


