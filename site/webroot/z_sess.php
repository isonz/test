<?php
//--------- CONFIG DEMO
define('_SESS_URL_', 'http://127.0.0.222');
define('_SESS_CLIENT_', 'me');

define('_SESS_CODE_', 'AUTH');
define('_SESS_TOKEN_', '64aeeeb10f2fa5b49969bf17e156e9ec');
define('_SESS_KEY_', 'DEMO@Key');

define('_SESS_CODE_', 'VCODE');
define('_SESS_TOKEN_', '74aeeeb10f2fa5b49969bf17e156e9ed');
define('_SESS_KEY_', 'DEMO.Key');
//---------- END CONFIG DEMO


$encode_data = array('session_id'=> session_id(), 'operate'=>'get');
$url_param = "client="._FILE_CLIENT_."&appCode="._FILE_CODE_.'&'.SecReq::setEncode(_FILE_TOKEN_, _FILE_KEY_, $encode_data);
$file_url = _FILE_URL_ ."/?".$url_param;
$avatar_url = _FILE_URL_ ."/avatar/?".$url_param;

Templates::Assign('file_url', $file_url);
Templates::Assign('avatar_url', $avatar_url);
Templates::Display('z_demo.html');


