<?php
/*
//$_GET = Security::getRequest('get');
$title = isset($_GET['t']) ? $_GET['t'] : '';
$action = isset($_GET['a']) ? $_GET['a'] : '';
//$title= addslashes($title);

if($title){
	//DB::Debug();
	$where =array('name' => $title);
	$test = Test::getList($where);
	var_dump($test);
}
*/

$str = "昨天=张小生&name2=今天";
$token = "token123456";
$key = "k125577";
$ext_data = "name3=明天&昨天=value4";

$encode = Func::enAES($str, $key);
var_dump($encode);
$decode = Func::deAES($encode, $key);
var_dump($decode);

$content = SecReq::send("http://localhost:8080/myssh/message/spost", $token, $str, $ext_data, $key);

var_dump($content);