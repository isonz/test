<?php
class RSA
{
	static private $_pubkey = "";
	static private $_prvkey = '';
	
	static private function _init()
	{
		if(!self::$_pubkey) self::$_pubkey = _DATA."Keys/pub.key";
		if(!self::$_prvkey) self::$_prvkey = _DATA."Keys/sso_private.key";
	}
	
	static public function setPubKey($pubkey)
	{
		self::$_pubkey = $pubkey;
	}
	
	static public function setPrvKey($prvkey)
	{
		self::$_prvkey = $prvkey;
	}
	
	static public function verify($plaintext, $md)
	{
		self::_init();
		$publickey = file_get_contents(self::$_pubkey);
		$md = base64_decode($md);
		$publickey = self::der2pemPub($publickey);
		$res = openssl_pkey_get_public($publickey);
		if (openssl_verify($plaintext, $md, $res) === 1){
			return true;
		}
		return false;
	}
	
	static public function der2pemPub($der_data)
	{
		$pem = chunk_split(base64_encode($der_data), 64, "\n");
		$pem = "-----BEGIN PUBLIC KEY-----\n".$pem."-----END PUBLIC KEY-----\n";
		return $pem;
	}
	
	static public function der2pemRsaPriv($der)
	{
		static $BEGIN_MARKER = "-----BEGIN PRIVATE KEY-----";
	    static $END_MARKER = "-----END PRIVATE KEY-----";
	    $value = base64_encode($der);
	    $pem = $BEGIN_MARKER . "\n";
	    $pem .= chunk_split($value, 64, "\n");
	    $pem .= $END_MARKER . "\n";
	    return $pem;
	}
	
	static public function ssoSignature($plaintext)
	{
		self::_init();
		if(!file_exists(self::$_prvkey)) return false;
		
		$privatekey = file_get_contents(self::$_prvkey);
		$privatekey = self::der2pemRsaPriv($privatekey);  //密钥为二进制码时需要

		$res = openssl_get_privatekey($privatekey);
		openssl_sign($plaintext, $sign, $res);
		openssl_free_key($res);
		$sign = base64_encode($sign);

		return $sign;
	}
	
	// plaintexts = array($login,$order_code,$price,$describle);
	// params = array('yzm'=>$yzm, 'type'=>$type);
	static public function toRsa(array $plaintexts, array $params=array(), array $textsignkey=array('plaintext', 'md'), $to='java', $splie='-1001-')
	{
		$textsign = self::getTextSign($plaintexts, $splie);
		$params[$textsignkey[0]] = $textsign['plaintext'];
		$params[$textsignkey[1]] = $textsign['sign'];
		if('java'==$to) $params = self::toJavaEncode($params);
		return $params;
	}
	
	static public function getTextSign($data, $splie='-1001-')
	{
		if(!is_array($data)) return false;
		$plaintext = implode($splie, $data);
		if(!$sign = self::ssoSignature($plaintext)){
			echo 'Create signature failed';
			return false;
		}
		return array('plaintext'=>$plaintext, 'sign'=>$sign);
	}
	
	static public function toJavaEncode($data)
	{
		if(!is_array($data)) return false;
		$encoded = "";
		while (list($k, $v) = each($data)){
			$encoded .= ($encoded ? '&' : '');
			$encoded .= rawurlencode($k)."=".rawurlencode($v);
		}
		return $encoded;
	}
}