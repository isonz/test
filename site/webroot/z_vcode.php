<?php
//sess
$config = array(
    'server_url' => 'http://127.0.0.222',
    'client_code' => 'me',
    'app_code'  => 'AUTH',
    'app_token' => '64aeeeb10f2fa5b49969bf17e156e9ec',
    'app_key'   => 'DEMO@Key'
);
$config['timeout'] = '1';   //second
Session::start($config);

//session_start();
$session_id = session_id();


//vcode
$config = array(
    'server_url' => 'http://127.0.0.223',
    'client_code' => 'me',
    'app_code'  => 'AUTH',
    'app_token' => '84aeeeb10f2fa5b49969bf17e156e9ed',
    'app_key'   => 'TEST.Key',
);
$config['timeout'] = 300;
$config['theme'] = 'default';
$config['wordnum'] = 4; //显示字符数

import(Vcode, 'VcodeSess');
VcodeSess::start($session_id, $config);

$op = isset($_GET['op']) ? $_GET['op'] : '';
$code = isset($_GET['code']) ? $_GET['code'] : '';
if('check'==$op){
    $code = strtolower($code);
    $check = VcodeSess::check($code);
    var_dump($check);
    if($check){
        VcodeSess::delete();
    }
}else{
    VcodeSess::show();
}


//<img src="http://127.0.0.8/z_vcode" />