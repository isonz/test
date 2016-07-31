<?php
error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","On");
ini_set('date.timezone','Asia/Shanghai');
//======================================= Basic
require_once 'E:/Z/LIB/core/config.php';

if(!defined('_SITE')){
    define('_SITE', dirname(__FILE__) . DS);
}
if(!defined('_MODS')){
	define('_MODS', _SITE . 'mods' . DS);
}
if(!defined('_MODULES')){
	define('_MODULES', _SITE . 'modules' . DS);
}
if(!defined('_DATA')){
	define('_DATA', _SITE . 'data' . DS);
}
if(!defined('_LOGS')){
    define('_LOGS', _DATA . 'logs' . DS);
}

foreach (glob(_MODS."/*.php") as $mods){
	require_once $mods;
}
foreach (glob(_MODULES."/*.php") as $modules){
	require_once $modules;
}

if('127.0.0.8:888'==$_SERVER['HTTP_HOST']){
    define('_DEVICE', 'admin' . DS);
}else if('127.0.0.8:999'==$_SERVER['HTTP_HOST']){
    define('_DEVICE', 'mo' . DS);
}else{
    $detect = new MobileDetect();
    if($detect->isMobile() || $detect->isTablet()){header("Location: http://127.0.0.8:999/");exit;}
    define('_DEVICE', 'pc' . DS);
}

if(!defined('_SMARTY_TEMPLATE')){
	define('_SMARTY_TEMPLATE', _SITE .'template' . DS . _DEVICE);
}
if(!defined('_SMARTY_COMPILED')){
	define('_SMARTY_COMPILED', _DATA . 'compileds' . DS . _DEVICE);
}
if(!defined('_SMARTY_CACHE')){
	define('_SMARTY_CACHE', _DATA . 'caches' . DS . _DEVICE);
}

//======================================== Config
$GLOBALS['CONFIG_DATABASE'] = array(
	'DEFAULT'=>array(
		'host'      => '127.0.0.1',
		'user'      => 'root',
		'pwd'       => 'admin888',
		'dbname'    => 'test',
		'port'      => 3306,
		'tb_prefix' => 'test_'
	)
);
$GLOBALS['CONFIG_SMTP'] = array(
	'server'    => "smtp.xxx.com",
	'port'      => 25,
	'email'     => "system@xxx.com",
	'user'      => "system@xxx.com",
	'passwd'    => ""
);

$GLOBALS['CONFIG_FTP'] = array(
	'server'    => "121.xx.xx.172",
	'user'      => "xx",
	'passwd'    => "xx",
	'port'      => 21,
	'passive'   => true,
	'timeout'	=> 90
);


//===================================
$GLOBALS['EXCLUDE_URL'] = array('vcode');


