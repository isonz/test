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
$session_id = session_id();

//vcode
$config = array(
    'server_url' => 'http://127.0.0.223',
    'client_code' => 'me',
    'app_code'  => 'TEST',
    'app_token' => '84aeeeb10f2fa5b49969bf17e156e9ed',
    'app_key'   => 'TEST.Key'
);
$config['timeout'] = 300;
$config['theme'] = 'default';

import(Vcode, 'VcodeSess');
VcodeSess::start($session_id, $config);
VcodeSess::show();


/*
import(Vcode, 'Vcode');
$image = Vcode::show();
header("Content-type: image/png");
$image = imagePng($image);
imagedestroy($image);
exit;
*/