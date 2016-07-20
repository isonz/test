<?php
//--------- CONFIG
define('_SESS_URL_', 'http://127.0.0.222');
define('_SESS_CLIENT_', 'me');

define('_SESS_CODE_', 'AUTH');
define('_SESS_TOKEN_', '64aeeeb10f2fa5b49969bf17e156e9ec');
define('_SESS_KEY_', 'DEMO@Key');

define('_SESS_CODE1_', 'VCODE');
define('_SESS_TOKEN1_', '74aeeeb10f2fa5b49969bf17e156e9ed');
define('_SESS_KEY1_', 'DEMO.Key');
//---------- END CONFIG

$config = array(
    'server_url' => _SESS_URL_,
    'client_code' => _SESS_CLIENT_,
    'app_code'  => _SESS_CODE_,
    'app_token' => _SESS_TOKEN_,
    'app_key'   => _SESS_KEY_
);

Session::start($config);



