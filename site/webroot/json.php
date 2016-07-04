<?php
$a = isset($_GET["a"]) ? $_GET["a"] : null;
$array = array(
	"person"=>array("name"=>"张珅", "age"=>27, "sex"=>"男"),
	'partment'=>array("name"=>"服务部", "num"=>6),
	"persons"=>array(
		array("name"=>"ison", "age"=>23, "sex"=>"男"),
		array("name"=>"katie", "age"=>33, "sex"=>"女"),
		array("name"=>"chen", "age"=>45, "sex"=>"男"),
		array("name"=>"yan", "age"=>21, "sex"=>"女")
	),
	'partments'=>array(
		array("name"=>"电商部", "num"=>6),
		array("name"=>"品牌部", "num"=>5),
		array("name"=>"市场部", "num"=>4),
		array("name"=>"人事部", "num"=>3),
	)
);

if($a){
	$new = isset($array[$a]) ? $array[$a] : array();
	$json = json_encode($new);
}else{
	$json = json_encode($array);
}
exit($json);
