<?php
class AdminUser extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_admin_user';
	
	
	static function login($username, $password)
	{
		$info = self::getInfoByName($username);
		$passwd = isset($info['passwd']) ? $info['passwd'] : null;
		$salt = isset($info['salt']) ? $info['salt'] : null;
		$password = md5(md5($password).$salt);
		if($passwd == $password) return true;
		return false;
	}
	
	static function changePassword($username, $password)
	{
		$condition = array("username" => $username);
		$info = self::getInfoByName($username);
		$salt = isset($info['salt']) ? $info['salt'] : null;
		$password = md5(md5($password).$salt);
		$data = array(
			"passwd" 	=> $password,
		);
		return self::update($condition, $data);
	}
	
	static function addUser($username, $nickname=null, $password=null, $orle=1)
	{
		if(!$username) return false;
		$condition = array(
			"username"	 	=> $username,
		);
		if(!self::check(array("condition"=>$condition))){
			$salt = Func::getRandomCode(10);
			$password = md5(md5($password).$salt);
			$data = array(
				"username"			=> $username,
				"nickname"			=> $nickname,
				"passwd"			=> $password,
				"salt"				=> $salt,
				"role"				=> $orle,
				"created_at"		=> Func::getTime(),					
			);
			return self::insert($data);
		}
		return false;
	}
	
	static function editUser($username, array $data)
	{
		if(!$username) return false;
		$condition = array(
				"username"	 	=> $username,
		);
		if(self::check(array("condition"=>$condition))){
			return self::update($condition, $data);
		}
		return false;
	}
	
	static function getInfoByName($name)
	{
		$info = self::getOne(array("username" => $name));
		return $info;
	}
	
	static function setOnline($username)
	{
		$condition = array("username" => $username);
		$data = array("is_online_bytime" => time());
		return self::update($condition, $data);
	}
	
	static function upLoginData($username)
	{
		$condition = array("username" => $username);
		$data = array(
			"last_login_ip"		=> Func::getIP(),
			"last_login_time"	=> Func::getTime(),
		);
		return self::update($condition, $data);
	}
	
	static function getNickName($username)
	{
		if(!$username) return null;
		$info = self::getOne(array("username" => $username), "nickname");
		return isset($info['nickname']) ? $info['nickname'] : null;
	}
	
}


