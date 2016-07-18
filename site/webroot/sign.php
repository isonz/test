<?php
$type = isset($_REQUEST['t']) ? $_REQUEST['t'] : null;
$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : null;
$errmsg = '';

if('ajax'==$type){
	switch ($a){
		case 'sigup':
			sigUp();
			break;
		case 'sigin':
			sigIn();
			break;
		default:
			ABase::toJson(1,'Parameter error!');
	}	
}else{
	if(isset($_GET['in'])){
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : null;
		$passwd = isset($_REQUEST['passwd']) ? $_REQUEST['passwd'] : null;
		$yzm = isset($_REQUEST['yzm']) ? $_REQUEST['yzm'] : null;
		require_once _LIBS."Vcode/Vcode.class.php";
		$vcode = strtolower(Vcode::getCode());
		if($yzm != $vcode){
			$errmsg = "验证码错误";
		}else{
			if(Users::login($username, $passwd)){
				$_SESSION['user'] = $username;
				$info = Users::getInfoByName($username, 'id');
				$_SESSION['userid'] = $info['id'];
				Users::upLoginData($username);
				header('Location: /');
			}else{
				$errmsg = "用户名或密码错误";
			}
		}
	}
	
	if(isset($_GET['out'])){
		session_destroy();
		session_unset();
		unset($_SESSION);
		header("Location: /");
		exit;
	}
	
	$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
	Templates::Assign('user', $user);
	Templates::Assign('errmsg', $errmsg);
	
	if(!$type){
		Templates::Display('sign.html');
	}else{
		Templates::Display('msign.html');
	}
}

function sigIn()
{
	global $POST;
	$username = $POST['username'];
	if(!Users::check(array("condition"=> array('username'=>$username)))) ABase::toJson(2,'用户名不存在');
	
	$passwd = $POST['passwd'];
	if(strlen($passwd) < 6) ABase::toJson(3,'至少需要6位');
	
	$yzm = $POST['yzm'];
	require_once _LIBS."Vcode/Vcode.class.php";
	$vcode = strtolower(Vcode::getCode());
	if($yzm != $vcode) ABase::toJson(4,'验证错误');
	
	$login = Users::login($username, $passwd);
	if(-1 == $login) ABase::toJson(5,'您的帐户还未激活');
	if(!$login) ABase::toJson(5,'密码不匹配');
	
	$_SESSION['user'] = $username;
	$info = Users::getInfoByName($username, 'id');
	$_SESSION['userid'] = $info['id'];
	Users::upLoginData($username);
	ABase::toJson(0,'Ok');
}

function sigUp()
{
	global $POST;
	$username = $POST['username'];
	if(!Func::checkEmail($username)) ABase::toJson(2,'邮箱格式错误');
	if(Users::check(array("condition"=> array('username'=>$username)))) ABase::toJson(8,'用户名被占用');
	
	$phone = $_POST['phone'];
	//if(!Func::checkmobile($phone)) ABase::toJson(3,'格式错误');
	
	$passwd = $POST['passwd'];
	$repasswd = $POST['repasswd'];
	if(strlen($passwd) < 6) ABase::toJson(4,'至少需要6位');
	if($passwd != $repasswd) ABase::toJson(5,'密码不匹配');
		
	$yzm = $POST['yzm'];
	require_once _LIBS."Vcode/Vcode.class.php";
	$vcode = strtolower(Vcode::getCode());
	if($yzm != $vcode) ABase::toJson(6,'验证错误');
	
	if(!Users::addUser($username, $phone, $passwd)) ABase::toJson(7,'注册失败');
	
	/*
	$_SESSION['user'] = $username;
	$info = Users::getInfoByName($username, 'id');
	$_SESSION['userid'] = $info['id'];
	Users::upLoginData($username);
	*/
	
	ABase::toJson(0,'Ok');
}


