<?php
require_once('../config.php');

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
	if(!file_exists($action)){
		header("Location: "._ASPUB."/html/404.html");
	}else{
		require_once $action;
	}
	exit;
}
include_once 'home.php';



