<?php
$config = array(
    'server_url' => 'http://127.0.0.222',
    'client_code' => 'me',
    'app_code'  => 'AUTH',
    'app_token' => '64aeeeb10f2fa5b49969bf17e156e9ec',
    'app_key'   => 'DEMO@Key'
);
$config['timeout'] = '100';   //second
Session::start($config);

$_SESSION['vcode'] = 'ison.zhang';
echo $_SESSION['vcode'];

//Session::delete('wx.age.s');
//session_destroy();



