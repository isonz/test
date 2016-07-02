<?php
class Address extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_address';	
	
	static public function add(array $data)
	{
		if(!$data) return false;
		
		$user_id = isset($data['user_id']) ? (int)$data['user_id'] : 0;
		$name = isset($data['name']) ? $data['name'] : '';
		$phone = isset($data['phone']) ? $data['phone'] : '';
		$province = isset($data['province']) ? (int)$data['province'] : 0;
		$city = isset($data['city']) ? (int)$data['city'] : 0;
		$county = isset($data['county']) ? (int)$data['county'] : 0;
		$address = isset($data['address']) ? $data['address'] : '';
		if(!$user_id || !$name || !$phone || !$province || !$city || !$county || !$address) return false;
		
		return self::insert($data);
	}
	
	static public function getInfo($id)
	{
		if(!$id) return array();
		
		$data = self::getData($id);
		$province = Countys::getName($data['province']);
		$city = Countys::getName($data['city']);
		$county = Countys::getName($data['county']);
		$data['pcc'] = $province.'省'.$city.'市'.$county;
		return $data;
	}
	
	static public function getInfoByUser($user_id, $ids=2)
	{
		$datas = self::getList("user_id='$user_id' AND module_id IN ($ids)", '*', "sort DESC, id DESC");
		$address = array();
		foreach($datas as $data){
			$province = Countys::getName($data['province']);
			$city = Countys::getName($data['city']);
			$county = Countys::getName($data['county']);
			
			$id = $data['id'];
			$address[$id]['pcc'] = $province.'省'.$city.'市'.$county;
			$address[$id]['addr'] = $data['address'];
			$address[$id]['postcode'] = $data['postcode'];
			$address[$id]['name'] = $data['name'];
			$address[$id]['phone'] = $data['phone'];
			$address[$id]['province'] = $data['province'];
			$address[$id]['city'] = $data['city'];
			$address[$id]['county'] = $data['county'];
		}
		return $address;
	}
	
	static public function setDefault($user_id, $id)
	{
		$user_id = (int)$user_id;
		$id = (int)$id;
		if(!$user_id || !$id) return false;

		self::update(array('user_id'=>$user_id, 'sort'=>9999), array('sort'=>0));
		self::update(array('user_id'=>$user_id, 'id'=>$id), array('sort'=>9999));
		return true;
	}
	
	static public function delete($user_id, $id)
	{
		//DB::Debug();
		$user_id = (int)$user_id;
		$id = (int)$id;
		if(!$user_id || !$id) return false;	
		$condition = array('user_id'=>$user_id, 'id'=>$id);
		return DB::Delete(static::$_table, $condition);
	}
	
}


