<?php
//--------- CONFIG DEMO
define('_FILE_URL_', 'http://127.0.0.211');
define('_FILE_CLIENT_', 'me');
define('_FILE_CODE_', 'test_demo');
define('_FILE_TOKEN_', '64aeeeb10f2fa5b49969bf17e156e9ec');
define('_FILE_KEY_', 'DEMO@Key');
//---------- END CONFIG DEMO

$username = 'demo';
$encode_data = array('username'=>$username);
$url_param = "client="._FILE_CLIENT_."&appCode="._FILE_CODE_.'&'.SecReq::setEncode(_FILE_TOKEN_, _FILE_KEY_, $encode_data);
$file_url = _FILE_URL_ ."/?".$url_param;
$avatar_url = _FILE_URL_ ."/avatar/?".$url_param;

Templates::Assign('file_url', $file_url);
Templates::Assign('avatar_url', $avatar_url);
Templates::Display('z_demo.html');


