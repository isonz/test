<?php
Abstract class ABase
{	
	static protected $_class = __CLASS__;
	
	static protected function _init()
	{
		if(__CLASS__ == static::$_class){
			exit('Cannot invoking abstract class '.__CLASS__);
		}
		self::myConfig();
	}
	
	static public function myConfig()
	{
		$mark = isset(static::$_dbconfig) ? static::$_dbconfig : 'A';
		return DB::myConfig($mark);
	}
	
	static function check($options = array())
	{
		self::_init();
		$options['one'] = 1;
		return DB::LimitQuery(static::$_table, $options);
	}
	
	static public function table()
	{
		self::_init();
		return static::$_table;
	}
	
	static function insert(array $data)
	{
		self::_init();
		return DB::Insert(static::$_table, $data);
	}
	
	static function getData($id, $select = '*', $order='')
	{
		$id = (int)$id;
		if($id < 1) return array();
		return self::getOne(array("id"=>$id), $select, $order);
	}
	
	static function getAll($select = '*', $order='id DESC')
	{
		self::_init();
		return DB::getRows(static::$_table, '', $select, $order);
	}
	
	static function getOne(array $where,  $select = '*', $order='')
	{
		if(!$where) return false;
		self::_init();
		return DB::getRow($where, static::$_table, $select, $order);
	}
	
	//$where 为字符串
	static function getList($where,  $select = '*', $order='id DESC')
	{
		if(!$where) return false;
		self::_init();
		return DB::getRows(static::$_table, $where, $select, $order);
	}
	
	//$condition 为 array 或 id
    static function update($condition, array $data)
    {
    	self::_init();
    	return DB::update(static::$_table, $condition, $data);
    }
    
    //自增某数字字段
    static function increase($where, $field, $n=1)
    {
    	self::_init();
    	if(!$where || !$field) return false;
    	$table = static::$_table;
    	$sql = "UPDATE $table SET $field=$field+$n WHERE $where";
    	$result = DB::Execute($sql);
    	return is_object($result) ? $result->rowCount() : false;
    }
    //自减某数字字段
    static function reduce($where, $field, $n=1)
    {
    	self::_init();
    	if(!$where || !$field) return false;
    	$table = static::$_table;
    	$sql = "UPDATE $table SET $field=$field-$n WHERE $where";
    	$result = DB::Execute($sql);
    	return is_object($result) ? $result->rowCount() : false;
    }
    //用IN更新多条记录 $where 字符串， $field 字符串 field=1
    static function inUpdate($where, $field)
    {
    	self::_init();
    	if(!$where || !$field) return false;
    	$table = static::$_table;
    	$sql = "UPDATE $table SET $field WHERE $where";
    	$result = DB::Execute($sql);
    	return is_object($result) ? $result->rowCount() : false;
    }
    
    //如果$field为空，则ID=$id 否则 按 field 内容
    static function del($id=0, array $field=array())
    {
    	self::_init();
    	if(!$id && !$field) return false;
    	self::_init();
    	if($id) $condition = array('id' => $id);
    	if($field) $condition = $field;
    	return DB::Delete(static::$_table, $condition);
    }

    static function paging($page, $page_size=50, $where = null, $order = null, $select = null)
    {
    	self::_init();
    	$data = Paging::getData(static::$_table, $page, $page_size, $where, $order, $select);
    	//$paging = Paging::getPage();
    	$paging = Paging::pagedShow();
    	$result['data'] = $data;
    	$result['page'] = $paging;
    	return $result;
    }

	static function log($error, $name='')
    {
    	error_log(date('Y-m-d H:i:s').", Error:".$error."\n\t", 3, _LOGS . $name.'code.'.date('Ymd').'.log');
    }
    
	static  function toJson($status=1, $msg='', $data=array(), $jsonp_callback=null)
    {
    	$status = (int)$status;
    	if($status>0) self::log($status.':'.$msg);
    	
    	if(!$jsonp_callback){
    		exit(json_encode(array('status'=>$status,'msg'=>$msg, 'data'=>$data)));
    	}else{
    		$json = json_encode(array('status'=>$status,'msg'=>$msg, 'data'=>$data));
    		exit("$jsonp_callback($json)");
    	}
    }
    
    static function token($token='')
    {
    	if(!session_id()) session_start();
    	if(!$token){
    		$token = $_SESSION['token'] = Func::getRandomCode(20);
    		return $token;
    	}else{
    		$stoken =  isset($_SESSION['token']) ? $_SESSION['token'] : '';
    		if(isset($_SESSION['token'])) unset($_SESSION['token']);
    		if($token === $stoken) return true;
    		return false;
    	}
    }
    
    static function RSASignature($plaintext,$url)
    {
    	if(!$plaintext || !$url) return '0,error001';
    	
    	if(!$sign = RSA::ssoSignature($plaintext)) ABase::toJson(0,'Create signature failed');
    	//------------------------ CURL post
    	$data = array('plaintext'=>$plaintext, 'md'=>$sign);
    	$encoded = "";
    	foreach ($data as $k => $v){
    		$encoded .= ($encoded ? '&' : '');
    		$encoded .= rawurlencode($k)."=".rawurlencode($v);
    	}
    	$pcontent = Func::curlPost($url, $encoded);
    	return $pcontent;
    }
    
}


