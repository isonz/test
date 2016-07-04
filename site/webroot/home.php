<?php
//$_GET = Security::getRequest('get');
$title = isset($_GET['t']) ? $_GET['t'] : '';
$title= addslashes($title);

if($title){
	DB::Debug();
	$where ="title='$title'";
	$test = Test::getList($where);
	var_dump($test);
}

exit;

$encode = encode("我的好朋友");
$decode = decode($encode);
var_dump($encode);
var_dump($decode);



function encode($tex, $key = "key123@ison")
{
	$chrArr = array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'0','1','2','3','4','5','6','7','8','9'
	);
	$key_b = $chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62];
	$rand_key = $key_b.$key;
	$rand_key=md5(md5($rand_key).$key_b);
	$key_p  = substr($rand_key, 0, 10);
	
	$texlen=strlen($tex);
	$reslutstr="";
	for($i=0;$i<$texlen;$i++){
		$reslutstr.=$tex{$i}^$rand_key{$i%32};
	}
	$reslutstr=trim($key_b.base64_encode($key_p.$reslutstr),"==");
	$reslutstr=substr(md5($reslutstr), 0,8).$reslutstr;
	return $reslutstr;
}

function decode($tex, $key = "key123@ison")
{
	if(strlen($tex)<24)return false;
	$verity_str=substr($tex, 0,8);
	$tex=substr($tex, 8);
	if($verity_str!=substr(md5($tex),0,8)) return false;  //完整性验证失败

	$key_b = substr($tex,0,6);
	$rand_key = $key_b.$key;
	$rand_key=md5(md5($rand_key).$key_b);
	$key_p  = substr($rand_key, 0, 10);
	
	$texs=base64_decode(substr($tex, 6));
	$kp = substr($texs, 0,10);
	if($key_p !== $kp) return false;	//$key检验
	
	$tex = substr($texs, 10);
	$texlen=strlen($tex);
	$reslutstr="";
	for($i=0;$i<$texlen;$i++){
		$reslutstr.=$tex{$i}^$rand_key{$i%32};
	}
	return $reslutstr;
}
