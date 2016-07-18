<?php
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


