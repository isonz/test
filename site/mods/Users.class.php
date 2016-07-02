<?php
class Users extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_users';
	
	
	static function login($username, $password)
	{
		if(!$username || !$password) return 0;
		$info = self::getInfoByName($username);
		$status = isset($info['status']) ? $info['status'] : 0;
		if($status < 1) return -1;
		
		$passwd = isset($info['passwd']) ? $info['passwd'] : null;
		$salt = isset($info['salt']) ? $info['salt'] : null;
		$password = md5(md5($password).$salt);
		if($passwd == $password) return 1;
		return 0;
	}
	
	/*
	 * if(!$flag) $err_msg = "密码修改失败"; if(-1==$flag) $err_msg = "新密码至少需要6位"; if(-2==$flag) $err_msg = "两次新密码输入不匹配"; if(-3==$flag) $err_msg = "先用密码验证不正确";
	 */
	static function changePassword($username, $password, $oldpasswd='', $repasswd='')
	{
		if(!$username || !$password) return 0;
		if(strlen($password) < 6) return -1;
		if($repasswd){
			if($repasswd != $password) return -2;
		}
		
		$condition = array("username" => $username);
		$info = self::getInfoByName($username);
		$salt = isset($info['salt']) ? $info['salt'] : null;
		
		if($oldpasswd){
			$opwd = $info['passwd'];
			$oldpasswd = md5(md5($oldpasswd).$salt);
			if($oldpasswd != $opwd) return -3;
		}
		
		$password = md5(md5($password).$salt);
		$data = array(
			"passwd" 	=> $password,
		);
		return self::update($condition, $data);
	}
	
	static function addUser($username, $phone=null, $password=null, $orle=1)
	{
		if(!$username) return false;
		if(!self::regSendEmail($username)) return false;
		if(!$phone) $phone = null;
		
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
	
	static function regSendEmail($email)
	{
		$smtp = new Smtp();
		//$smtp->debug = TRUE;
		$str = $email."-1001-".time();
		$href = "http://".$_SERVER['HTTP_HOST']."/user/?a=activate&activatecode=".Func::encode($str);
		$from_name = "奥莲商服";
		$mailsubject = '激活您的账号';
		$mailbody = '感谢您注册奥莲商服。点击下面链接激活您的账号<a href="'.$href.'">'.$href.'</a> &nbsp;&nbsp;&nbsp; 此链接24小时内有效';
		
		$send = $smtp->sendmail($email, $from_name, $mailsubject, $mailbody);
		return $send;
	}
	
	static function passwdSendEmail($email)
	{
		$smtp = new Smtp();
		//$smtp->debug = TRUE;
		$str = $email."-1001-".time();
		$href = "http://".$_SERVER['HTTP_HOST']."/user/?a=findpasswd&code=".Func::encode($str);
		$from_name = "奥莲商服";
		$mailsubject = '找回您的密码';
		$mailbody = '点击下面链接找回您账户的密码：<a href="'.$href.'">'.$href.'</a> &nbsp;&nbsp;&nbsp; 此链接一小时内有效';
	
		$send = $smtp->sendmail($email, $from_name, $mailsubject, $mailbody);
		return $send;
	}
	
	static function editUser($username, array $data)
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
			"login_ip"		=> Func::getIP(),
			"login_at"	=> Func::getTime(),
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


