<?php
/**
 * @author ison.zhang
 */

class Security
{
	//返 回 值：返回检测结果，ture or false
	static function isInject($sql = null)
	{
		if(!$sql) return false;
		return preg_match('/select|insert|update|delete|eval|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql);    // 进行过滤
	}

	//返 回 值：返回处理后的Int
	static function getInt($id = 0) 
	{
		if (!$id || self::isInject($id) || !is_numeric($id)) return 0;
		$id = intval($id);
		return  $id;
	}
	
	//返 回 值：返回处理后的Float
	static function getFloat($id = 0)
	{
		if (!$id || self::isInject($id) || !is_float($id)) return 0;
		$id = floatval($id);
		return  $id;
	}

	//返 回 值：返回过滤后的字符串
	static function getString($str = null) 
	{
		if(!$str) return '';
		if(self::isInject($str)) return '';

		$str = addslashes($str);    // 进行过滤
		//$str = str_replace('-', '\_', $str);    // 把 '-'过滤掉
		$str = str_replace('%', '\%', $str);    // 把 '%'过滤掉
			
		//return mysql_real_escape_string($str);
		return $str;
	}

	static function getHtmlStr($str) 
	{
		if(!$str) return '';
		$str = self::getString($str);
		$str = nl2br($str);    			// 回车转换
		$str = htmlspecialchars($str);    // html标记转换
		return $str;
	}
	
	static function getRequest($type = 'post')
	{
		$req = null;
		switch ($type){
			case 'post':
				$req = $_POST; break;
			case 'get':
				$req = $_GET; break;
			case 'request':
				$req = $_REQUEST; break;
			default:
				return array(); break;
		}
		if(!$req || !is_array($req)) return array();
		
		$arr = array();
		foreach ($req as $key=>$val)
		{
			if (is_numeric($val)) {
				$arr[self::getString($key)] = self::getInt($val);
			} else if(is_array($val)) {
				$arr[self::getString($key)] = self::loopArray($val);
			}else{
				$arr[self::getString($key)] = self::getString($val);
			}
		}
		return $arr;
	}
	
	static function loopArray(array $arr = array())
	{
		if(!is_array($arr)) return self::getString($arr);
		$tmp = array();
		foreach ($arr as $k => $v){
			if(is_array($v)){
				self::loopArray($v);
			}else{
				$tmp[self::getString($k)] = self::getString($v);
			}
		}
		return $tmp;
	}
	
}
