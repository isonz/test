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

$str = "name1=value1&name2=value2";
$encode = Func::enAES($str, "key123@ison");
var_dump($encode);
$decode = Func::deAES($encode, "key123@ison");
var_dump($decode);

//$json = SecReq::send("http://localhost:8080/myssh/message/spost", "123456", "name1=value1&name2=value2", "name3=value3&name4=value4", "k125");

