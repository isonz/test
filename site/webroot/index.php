<?php
require_once('../config.php');

//---------- Security
$POST = Security::getRequest('post');	//安全
$GET = Security::getRequest('get');
$REQUEST = Security::getRequest('request');
//------------ End Security

//---------------- 控制器
$uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : null;
$action = null;
if($uri){
	$uri = explode("/", $uri);
	$action = isset($uri[1]) ? $uri[1] : null;
	$action = explode("?", $action); 
	$action = isset($action[0]) ? $action[0] : null;
}

/*
//----------------- user
Session::start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
Templates::Assign('user', $user);
*/

Templates::Assign('action', $action);
if($action){
	$action = $action.".php";
	$flag = 0;
	foreach (glob("*.php") as $webroot){
		if($action === $webroot){
			require_once $action;
			$flag = 1;
			exit;
		}
	}
	if(!$flag){
		header("Location: /misc/html/404.html");
		exit;
	}
}
include_once 'home.php';



