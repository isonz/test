<?php
/*
 * depend SecReq.class.php
 */
//import(_LIBS, 'SecReq');
class Session
{
    static private $_sessname   = 'PHPSESSID';
    static private $_savepath   = '';
    static private $_config     = array();

    static private function _init($operate='read')
    {
        $server_url = self::$_config['server_url'];
        $client_code = self::$_config['client_code'];
        $app_code = self::$_config['app_code'];
        $app_token = self::$_config['app_token'];
        $app_key = self::$_config['app_key'];

        $sessionid = session_id();
        $encode_data = array('session_id'=> $sessionid, 'operate'=>$operate);
        $ext_send_data = array('client_code'=>$client_code, 'app_code'=>$app_code);
        $data = SecReq::send($server_url, $app_token, $encode_data, $ext_send_data, $app_key);
        return $data;
    }

    //$config['server_url']=; $config['client_code']=; $config['app_code']=; $config['app_token']=; $config['app_key']=;
    static public function start(array $config=array(), $session_name='', $expiry_time=86400)
    {
        if(!isset($config['server_url']) || !isset($config['client_code']) || !isset($config['app_code']) || !isset($config['app_token']) || !isset($config['app_key'])) exit('Session Config Error !');
        self::$_config = $config;

        $handler = new Session();
        ini_set('session.save_handler','user');
        session_set_save_handler(
            array($handler, 'open'),
            array($handler, 'close'),
            array($handler, 'read'),
            array($handler, 'write'),
            array($handler, 'destroy'),
            array($handler, 'gc')
        );

        if(!$session_name) $session_name = 'PHPSESSID';
        $expiry_time = (int)$expiry_time;
        if(!$expiry_time){
            $expiry_time = null;
        }else{
            $expiry_time = time() + $expiry_time;
        }

        $SESSIONID = isset($_COOKIE[$session_name]) ? $_COOKIE[$session_name] : null;
        if($SESSIONID) session_id($SESSIONID);
        session_start();
        $sessionid = session_id();
        setcookie($session_name, $sessionid, $expiry_time, '/');

        self::$_sessname = $session_name;
        return $sessionid;
    }

    static public function open($save_path, $session_name)
    {
        self::$_sessname = $session_name;
        self::$_savepath = $save_path;
        return true;
    }

    static public function close()
    {
        session_write_close();
        return true;
    }

    static public function read($id)
    {
        $data = self::_init();
        var_dump($data);
        return '';
    }

    static public function write ($id, $data)
    {
        return '';
    }

    static public function destroy($id)
    {
        return '';
    }

    static public function gc($expiry_time)
    {
        return true;
    }
}





