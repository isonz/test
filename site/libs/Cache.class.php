<?php
class Cache
{
	private static $_save_entity = null;		// file, memcache
	private static $_entity = array();	
	
	static private function _getSaveEntity()
	{
		if(!self::$_save_entity){
			self::$_save_entity = isset($GLOBALS['SETTING']['session_save_entity']) ? $GLOBALS['SETTING']['session_save_entity'] : 'file';
		}
		return self::$_save_entity;
	}
	
	static private function _init()
	{
		self::_getSaveEntity();
		switch (self::$_save_entity){
			case 'memcache':
				self::_initMemcache();
				break;
			case 'file':
				self::_initFile();
		}
	}
	
	static private function _initFile()
	{
		if(!session_id()) session_start();
		$_SESSION['Cache']  = array();
		self::$_entity = $_SESSION['Cache'];
		return self::$_entity;
	}
	
	static private function _initMemcache()
	{
		if(!self::$_entity){
				
		}
		return self::$_entity;
	}
	
	static private function _save()
	{
		switch (self::$_save_entity){
			case 'memcache':
				return self::_saveMemcache();
			case 'file':
				return self::_saveFile();
		}
	}
	
	static private function _saveFile()
	{
		if(!session_id()) session_start();
		$_SESSION['Cache']  = self::$_entity;
		return self::$_entity;
	}
	
	static private function _saveMemcache()
	{
		//
	}
	
	static private function _getMemcache()
	{
		//
	}
	
	static private function _getFile()
	{
		if(!session_id()) session_start();
		self::$_entity = isset($_SESSION['Cache']) ? $_SESSION['Cache'] : null;
		return self::$_entity;
	}
	
	static public function getEntity()
	{
		self::_getSaveEntity();
		switch (self::$_save_entity){
			case 'memcache':
				return self::_getMemcache();
				break;
			case 'file':
			default:
				return self::_getFile();
		}
		return self::$_entity;
	}
	
	static public function clearTokenImage($token, $pic_id)
	{
		self::_getSaveEntity();
		switch (self::$_save_entity){
			case 'memcache':
				return self::_clearMemcacheImage($token, $pic_id);
			case 'file':
				return self::_clearFileImage($token, $pic_id);
		}
		return false;
	}
	
	static private function _clearMemcacheImage($token, $pic_id)
	{
		//
	}
	
	static private function _clearFileImage($token, $pic_id)
	{
		$pic_id = (int)$pic_id;
		if(!$pic_id) return false;
		if(!session_id()) session_start();
		$picids = isset($_SESSION['Cache']['token'][$token]) ? $_SESSION['Cache']['token'][$token] : array();
		if(!$picids) return true;
		foreach ($picids as $k => $picid){
			if($picid == $pic_id){
				unset($_SESSION['Cache']['token'][$token][$k]);
				break;
			}
		}
		return true;
	}
	
    static public function setImageToken(array $images = array())
    {
    	self::_init();
		$str = time().rand(10000000, 99999999);
		$str = md5($str);
		self::$_entity['token'][$str] = $images;
		self::_save();
		return $str;
    }
    
    static public function getEntityToken()
    {
    	self::getEntity();
    	return isset(self::$_entity['token']) ? self::$_entity['token'] : array();
    }
    
    static public function getToken()
    {
    	$token = self::getEntityToken();
    	$token = key($token);
    	return $token;
    }
    
    
    
}

