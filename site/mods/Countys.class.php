<?php
class Countys extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_countys';	
	
	static public function getCountys($pid=0)
	{
		$pid = (int)$pid;
		$where = "pid=$pid AND status=1";
		$info = self::getList($where, 'id,name', 'sort DESC,name ASC');
		return $info;
	}
	
	static public function getName($id)
	{
		$data = self::getData($id);
		return $data['name'];
	}
	
}


