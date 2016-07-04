<?php
class SecReq
{
    const SEPARATOR = '-1001-';

	//Use: if(false !== $data) return true;
    //$encode_data需要加密的数据，$ext_send_data额外明文发送的数据
	static public function send($url, $token, array $encode_data, array $ext_send_data=array(), $encode_key='', $session=false)
	{ 
		if(!$url || !$token || !$encode_data){
			ModsBase::log("SecReq.send[url:$url, token:$token, data:".serialize($encode_data)."]");
			return false;
		}

		$dataStr = implode(self::SEPARATOR, $encode_data);
		$time = time();
		$sign = $token . self::SEPARATOR . $time . self::SEPARATOR . $dataStr;
		if($encode_key){
			$sign = Func::encode($sign, $encode_key);
		}else{
			$sign = Func::encode($sign);
		}

		$post['sign'] = $sign;
        $post['md'] = md5($sign);
		if($ext_send_data){
			$extDataStr = implode('', $ext_send_data);
			$md = md5($sign.$extDataStr);
			$post['md'] = $md;
			$post = array_merge($post, $ext_send_data);
		}

		if($session) session_write_close();
		$pContent = Func::curlPost($url, $post);
		if($session) session_start();

		if($pContent) $pContent = json_decode($pContent, true);
		if(!$pContent){
			ModsBase::log("SecReq.send[pContent:".serialize($pContent)."]");
			return false;
		}

		$status = isset($pContent['status']) ? (int)$pContent['status'] : 1;
		$msg = isset($pContent['msg']) ? $pContent['msg'] : '';
		$data = isset($pContent['data']) ? $pContent['data'] : array();
		if($status || 'SUCCESS'!=$msg){
			ModsBase::log("SecReq.send[status:$status, msg:$msg]");
			return false;
		}
		return $data;
	}

    //获取加密信息:$decode_key为解密密钥，$timeout为超时时间（秒）
	static public function get($token, $timeout=10, $decode_key='', $is_md=true)
	{
        if(!$token){
            ModsBase::log("SecReq.get [Have not token]");
            return false;
        }
        $timeout = (int)$timeout;
        if($timeout < 10) $timeout = 10;

        global $REQUEST;
        $sign = isset($REQUEST['sign']) ? $REQUEST['sign'] : '';
        if(!$sign){
            ModsBase::log("SecReq.get[sign:$sign]");
            return false;
        }
        unset($REQUEST['sign']);

        if($is_md){
            $md = isset($REQUEST['md']) ? $REQUEST['md'] : '';
            if(!$md){
                ModsBase::log("SecReq.get[md:$md]");
                return false;
            }
            unset($REQUEST['md']);
            $reqStr = implode('', $REQUEST);
            $str = Func::dictSortStr($sign.$reqStr); //字典排序:防止参数位置不对产生的验证不通过
            $smd = md5($str);
            if($md != $smd){
                ModsBase::log("SecReq.get[validation_md:$smd, request_md:$md]");
                return false;
            }
        }

        if($decode_key){
            $decode = Func::decode($sign, $decode_key);
        }else{
            $decode = Func::decode($sign);
        }
        if(!$decode){
            ModsBase::log("SecReq.get [decode fail for key:$decode_key]");
            return false;
        }
        $decode = explode('-1001-', $decode);
        $dToken = isset($decode[0]) ? $decode[0] : '';
        if(!$dToken || $dToken != $token){
            ModsBase::log("SecReq.get[decode_token:$dToken, parameter_token:$token]");
            return false;
        }
        $time = isset($decode[1]) ? (int)$decode[1] : 0;
        $now = time();
        if($now - $time > $timeout){
            ModsBase::log("SecReq.get[now:$now, decode_time:$time, timeout:$timeout]");
            return false;
        }
        unset($decode[0]);
        unset($decode[1]);
        $decode = array_values($decode);
        return $decode;
	}

    //生成加密数据
    static public function setEncode($token, $encode_key='', array $encode_data=array(), $return_add_param=true)
    {
        if(!$token){
            ModsBase::log("SecReq.setEncode[token:$token]");
            return false;
        }
        $dataStr = implode(self::SEPARATOR, $encode_data);
        $time = time();
        $sign = $token . self::SEPARATOR . $time . self::SEPARATOR . $dataStr;
        if($encode_key){
            $sign = Func::encode($sign, $encode_key);
        }else{
            $sign = Func::encode($sign);
        }
        if($return_add_param) return "sign=$sign";
        return $sign;
    }

    //使用RSA签名:$plaintext为明文。 Use: $plaintext = $username. SecReq::SEPARATOR .$vcode;;
    static function RSASignature($plaintext, $url)
    {
        if(!$plaintext || !$url) return '0,error001';

        if(!$sign = RSA::ssoSignature($plaintext)) ModsBase::toJson(0,'Create signature failed');
        //------------------------ CURL post
        $data = array('plaintext'=>$plaintext, 'md'=>$sign);
        $encoded = "";
        foreach ($data as $k => $v){
            $encoded .= ($encoded ? '&' : '');
            $encoded .= rawurlencode($k)."=".rawurlencode($v);
        }
        $pContent = Func::curlPost($url, $encoded);
        return $pContent;
    }

}
