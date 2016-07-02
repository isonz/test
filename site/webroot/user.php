<?php
$type = isset($_REQUEST['t']) ? $_REQUEST['t'] : null;
$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : null;
$username = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if('ajax'==$type){
	switch ($a){
		case 'findpasswd':
			findpasswd();
			break;
		case 'setpasswd':
			setpasswd();
			break;
		default:
			if(!$username){header("Location: /"); exit;}
			ABase::toJson(1,'Parameter error!');
	}
}else{
	switch ($a){
		case 'findpasswd':
			findpasswd();
			break;
		case 'activate':
			activate();
			break;
		case 'reactivate':
			if(!$username){header("Location: /"); exit;}
			reactivate();
			break;
		case 'edit':
		case 'editpasswd':
			home($a);
			break;
		default:
			if(!$username){header("Location: /"); exit;}
			home();
	}
}

function home($a='')
{
	global $POST;
	$username = isset($_SESSION['user']) ? $_SESSION['user'] : null;
	$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
	$edit = '';
	$err_msg = '';
	if('edit'==$a){
		$edit = 'edit';
		$editsave = isset($_REQUEST['editsave']) ? $_REQUEST['editsave'] : 0;
		if($editsave){
			$metainfo = UsersMeta::getOne(array('id' => $userid));
			$address_id = isset($metainfo['address_id']) ? $metainfo['address_id'] : 0;
			if($address_id){
				Address::update($address_id, array('name'=>$_POST['name'], 'phone'=>$POST['phone'], 'province'=>$_POST['province'], 'city'=>$_POST['city'], 'county'=>$_POST['county'], 'address'=>$_POST['address'], 'postcode'=>$_POST['postcode']));
			}else{
				$address_id = Address::insert(array('user_id'=>$userid, 'name'=>$_POST['name'], 'phone'=>$POST['phone'], 'province'=>$_POST['province'], 'city'=>$_POST['city'], 'county'=>$_POST['county'], 'address'=>$_POST['address'], 'postcode'=>$_POST['postcode']));
			}
			UsersMeta::update($userid, array('name'=>$_POST['name'], 'phone'=>$POST['phone'], 'address_id'=>$address_id));
		}
	}
	
	$editpasswd = '';
	if('editpasswd'==$a){
		$editpasswd = 'editpasswd';
		$passwdsave = isset($_REQUEST['passwdsave']) ? $_REQUEST['passwdsave'] : 0;
		if($passwdsave){
			$oldpasswd = $POST['oldpasswd'];
			$passwd = $POST['passwd'];
			$repasswd = $POST['repasswd'];
			$flag = Users::changePassword($username, $passwd, $oldpasswd, $repasswd);
			if(!$flag) $err_msg = '<font color="red">密码修改失败，请勿使用之前使用过的密码</font>';
			if(-1==$flag) $err_msg = '<font color="red">新密码至少需要6位</font>';
			if(-2==$flag) $err_msg = '<font color="red">两次新密码输入不匹配</font>';
			if(-3==$flag) $err_msg = '<font color="red">现用密码不正确</font>';
			if($flag > 0) $err_msg = '<font color="green">密码修改成功</font>';
		}
	}
	
	$metainfo = UsersMeta::getOne(array('id' => $userid));
	$name = isset($metainfo['name']) ? $metainfo['name'] : '';
	$sex = isset($metainfo['sex']) ? $metainfo['sex'] : 0;
	$birth = isset($metainfo['birth']) ? $metainfo['birth'] : '';
	$address_id = isset($metainfo['address_id']) ? $metainfo['address_id'] : 0;
	$sex = $sex ? "男" : "女";
	$metainfo['sex'] = $sex;
	
	$addressinfo = Address::getInfo($address_id);
	$provinces = Countys::getCountys();
	$citys = '';
	$countys = '';
	if(isset($addressinfo['province'])) $citys = Countys::getCountys($addressinfo['province']);
	if(isset($addressinfo['city'])) $countys = Countys::getCountys($addressinfo['city']);

	Templates::Assign('err_msg', $err_msg);
	Templates::Assign('citys', $citys);
	Templates::Assign('countys', $countys);
	Templates::Assign('provinces', $provinces);
	Templates::Assign('edit', $edit);
	Templates::Assign('editpasswd', $editpasswd);
	Templates::Assign('username', $username);
	Templates::Assign('metainfo', $metainfo);
	Templates::Assign('addressinfo', $addressinfo);
	Templates::Display('user/home.html');
}

function setpasswd()
{
	$passwd = isset($_POST['passwd']) ? $_POST['passwd'] : '';
	$repasswd = isset($_POST['repasswd']) ? $_POST['repasswd'] : '';
	$code = isset($_POST['code']) ? $_POST['code'] : '';
	if(!$code) ABase::toJson(2,'验证信息错误');
	if(strlen($passwd) < 6) ABase::toJson(3,'密码至少需要6位');
	if($passwd != $repasswd) ABase::toJson(4,'两次密码输入不匹配');
	$str = Func::decode($code);
	$str = explode("-1001-", $str);
	$username = isset($str[0]) ? $str[0] : '';
	$time = isset($str[1]) ? (int)$str[1] : 0;
	if(time()-$time > 3600) ABase::toJson(5,'验证信息已过期');
	if(!$username) ABase::toJson(6,'获取用户信息失败');
	if(!Users::check(array("condition"=> array('username'=>$username)))) ABase::toJson(8,'账户不存在');
	if(!Users::changePassword($username, $passwd)) ABase::toJson(7,'密码修改失败，请勿使用之前用过的密码');
	ABase::toJson(0,'Ok');
}

function findpasswd()
{
	$error = "";
	$code = isset($_GET['code']) ? $_GET['code'] : '';
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	if($code){
		$str = Func::decode($code);
		$str = explode("-1001-", $str);
		$username = isset($str[0]) ? $str[0] : '';
		$time = isset($str[1]) ? (int)$str[1] : 0;
		if(time()-$time > 3600){
			$error = "验证信息已过期，请<a href='/user/?a=findpasswd'>重新找回</a>";
		}else{
			if(!$username) $error = "获取用户信息失败，请<a href='/user/?a=findpasswd'>重新找回</a>";
		}
	}else if($username){
		if(!Func::checkEmail($username)) ABase::toJson(2,'邮箱格式错误');
		if(!Users::check(array("condition"=> array('username'=>$username)))) ABase::toJson(8,'账户不存在');
		
		$yzm = isset($_POST['yzm']) ? $_POST['yzm'] : '';
		require_once _LIBS."Vcode/Vcode.class.php";
		$vcode = strtolower(Vcode::getCode());
		if($yzm != $vcode) ABase::toJson(3,'验证码错误');
		
		if(!Users::passwdSendEmail($username)) ABase::toJson(4,'邮件发送失败，请稍后再试');
		ABase::toJson(0,'Ok');
	}
	Templates::Assign('username', $username);
	Templates::Assign('code', $code);
	Templates::Assign('error', $error);
	Templates::Display('user/findpasswd.html');
}

function activate()
{
	$error = "";
	$activatecode = isset($_GET['activatecode']) ? $_GET['activatecode'] : '';
	if(!$activatecode){
		$error = "参数错误，请<a href='/user/?a=reactivate'>重新验证</a>";
	}else{
		$error = "验证失败，请<a href='/user/?a=reactivate'>重新验证</a>";
		$str = Func::decode($activatecode);
		$str = explode("-1001-", $str);
		$username = isset($str[0]) ? $str[0] : '';
		$time = isset($str[1]) ? (int)$str[1] : 0;
		if(time()-$time > 86400){
			$error = "验证信息已过期，请<a href='/user/?a=reactivate'>重新验证</a>";
		}else{
			$error = "获取用户信息失败，请<a href='/user/?a=reactivate'>重新验证</a>";
			if($username){
				$error = "恭喜您，邮箱通过验证，点击进入 <a href='/'>奥莲商服主页</a>, 或 <a href='javascript:sigin();'>立即登入</a>";
				if(!Users::update(array('username'=>$username), array("status"=>1))) $error = "验证失败，用户已通过验证 <a href='javascript:sigin();'>立即登入</a>";
			}
		}		
	}
	Templates::Assign('activatecode', $activatecode);
	Templates::Assign('error', $error);
	Templates::Display('user/activate.html');
}

function reactivate()
{
	$error = "";
	$reactivate = "1";
	$username = isset($_SESSION['user']) ? $_SESSION['user'] : null;
	$info = Users::getInfoByName($username, 'status');
	$status = (int)$info['status'];
	if($status > 0){
		$error = "您已经通过邮箱认证了，谢谢您的使用，点击进入 <a href='/user/'>我的信息</a>";
	}else{
		$error = "已经重新发送验证链接到您的邮箱，请登入您的邮箱进行验证。";
		Users::regSendEmail($username);
	}
	Templates::Assign('reactivate', $reactivate);
	Templates::Assign('error', $error);
	Templates::Display('user/activate.html');
}

