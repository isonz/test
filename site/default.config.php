<?php
error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","On");

//======================================= Basic
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if(!defined('_SITE')){
    define('_SITE', dirname(__FILE__) . DS);
}
if(!defined('_LIBS')){
    define('_LIBS', _SITE . 'libs' . DS);
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
if(!defined('_SMARTY')){
	define('_SMARTY', _LIBS . 'Smarty' . DS);
}

foreach (glob(_LIBS."/*.php") as $libs){
	require_once $libs;
}
foreach (glob(_LIBS."/Vcode/*.php") as $vcode){
	require_once $vcode;
}
foreach (glob(_MODS."/*.php") as $mods){
	require_once $mods;
}
foreach (glob(_SMARTY."/*.php") as $lib_smarty){
	require_once $lib_smarty;
}
foreach (glob(_MODULES."/*.php") as $modules){
	require_once $modules;
}

//------------------------ encoding
if(!defined('_ENCODING')){
	define('_ENCODING', 'UTF-8');
}
//======================================= Smarty
$detect = new MobileDetect();
if($detect->isMobile() || $detect->isTablet()){header("Location: http://127.0.0.61/");exit;}

if('127.0.0.61'==$_SERVER['HTTP_HOST']){
	define('_DEVICE', 'mo' . DS);
}else if('127.0.0.62'==$_SERVER['HTTP_HOST']){
	define('_DEVICE', 'admin' . DS);
}else{
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
	'host'      => '127.0.0.1',
    'user'      => 'root',
    'pwd'       => 'admin888',
    'dbname'    => 'onionhealth',
	'port'      => 3306,
	'tb_prefix' => 'oh_'
);

$GLOBALS['EXCLUDE_URL'] = array('vcode');






