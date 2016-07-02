<?php
class Func
{
	//时间格式转换
	static public function getTime($time = 0)
	{
		if(!$time) return date('Y-m-d H:i:s');
		if((int)$time > 0) return date('Y-m-d H:i:s', $time);
		return strtotime($time);
	}

	//获取客户端IP地址
	static public function getIP()
	{
		if (getenv('HTTP_CLIENT_IP')){
      		$ip = getenv('HTTP_CLIENT_IP'); 
     	}elseif (getenv('HTTP_X_FORWARDED_FOR')){
      		$ip = getenv('HTTP_X_FORWARDED_FOR');
		}elseif (getenv('HTTP_X_FORWARDED')){ 
         	$ip = getenv('HTTP_X_FORWARDED');
     	}elseif (getenv('HTTP_FORWARDED_FOR')){
         	$ip = getenv('HTTP_FORWARDED_FOR');
		}elseif (getenv('HTTP_FORWARDED')){
         	$ip = getenv('HTTP_FORWARDED');
     	}else {
          	$ip = $_SERVER['REMOTE_ADDR'];
     	}
     	return $ip;
	}
	
	//获取当前完整的带参数的URL
	static public function getCurrentURL()
	{
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}$pageURL .= "://";
	
		if ($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		$urlpas = explode('?', $pageURL);
		if(isset($urlpas[1])) $pageURL = $urlpas[1];
		$urlpara = self::convertUrlQuery($pageURL);
		if(count($urlpara) > 1) $pageURL = self::getUrlQuery($urlpara);
		if(isset($urlpas[1])) $pageURL = $urlpas[0]."?".$pageURL;
		return $pageURL;
	}
	
	//产生随机码 $n 为随机码长度
	static function getRandomCode($n)
	{
		$tt = null;
		$ss=array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'1','2','3','4','5','6','7','8','9','0'
		);
		for ($i=0; $i<$n; $i++){
			$tt .= $ss[rand(0, 61)];
		} 
		return $tt;
	}

	//动态加密字符串,tex不能是数字
	static public function encodeStr($tex, $type = "encode", $key = "key123@ison")
    {
		$chrArr = array(
					'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
	                'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
	                '0','1','2','3','4','5','6','7','8','9'
				);
	    if($type=="decode"){
	        if(strlen($tex)<14)return false;
	        $verity_str=substr($tex, 0,8);
	        $tex=substr($tex, 8);
	        if($verity_str!=substr(md5($tex),0,8)){
	            //完整性验证失败
	            return false;
	        }
	    }
	    $key_b = $type == "decode" ? substr($tex,0,6):$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62];
	    $rand_key = $key_b.$key;
	    $rand_key=md5($rand_key);
	    $tex=$type=="decode"?base64_decode(substr($tex, 6)):$tex;
	    $texlen=strlen($tex);
	    $reslutstr="";
	    for($i=0;$i<$texlen;$i++){
	        $reslutstr.=$tex{$i}^$rand_key{$i%32};
	    }
	    if($type!="decode"){
	        $reslutstr=trim($key_b.base64_encode($reslutstr),"==");
	        $reslutstr=substr(md5($reslutstr), 0,8).$reslutstr;
	    }
	    return $reslutstr;
	}
	
	//程序运行内存消耗
	static public function showMemory()
	{
		$size = memory_get_usage(true);
		$unit=array('b','kb','mb','gb','tb','pb');
		$size = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		echo '<br>程序运行内存消耗: ' . $size;
	}
	
	/* 无限极分类树形
	 * $items = array(
     *			1 => array('id' => 1, 'pid' => 0, 'name' => '安徽省'),
     *			2 => array('id' => 2, 'pid' => 0, 'name' => '浙江省'),
     *			3 => array('id' => 3, 'pid' => 1, 'name' => '合肥市'),
     *			4 => array('id' => 4, 'pid' => 3, 'name' => '长丰县'),
     *			5 => array('id' => 5, 'pid' => 1, 'name' => '安庆市'),
     * );
     * $tree = self::cateGenTree($items,'id','pid','son');
     * print_r($tree);
	 */
	static function cateGenTree($items,$id='id', $fid='fid', $son='son')
	{
		$tree = array();
		foreach($items as $item){
			if(isset($items[$item[$fid]])){
				$items[$item[$fid]][$son][] = &$items[$item[$id]];
			}else{
				$tree[] = &$items[$item[$id]];
			}
		}
		return $tree;
	}
	
	/* 格式化的树形数据 如
	 * Array(
     *	[2] => 男装
     *	[5] => -羽绒棉服
     *	[6] => --红色
     *	[1] => 女装
     *	[3] => -羽绒棉服
	 *	)
	 */
	static public  $_array_tmp = array();
	static function formatTreeData($tree, $id='id', $title='title', $son='son', $i=0)
	{
		$symbol = '';
		for($j=$i; $j>0; $j--){
			$symbol .= '--';
		}
		foreach($tree as $t){
			self::$_array_tmp[$t[$id]] = $symbol . $t[$title];
			if(isset($t[$son])){
				$k = $i;
				$k++;
				self::formatTreeData($t[$son],$id, $title, $son, $k);
			}
		}
		return self::$_array_tmp;
	}
	
	//获取 URL 的主域名
	static function getUrlDomain($url)
	{
		if(!$url) return false;
		$domain = parse_url($url);
		$domain = strtolower($domain['host']);
		$domain = explode('.', $domain);
		$len = count($domain);
		$domain = $domain[$len-2].'.'.$domain[$len-1];
		return $domain;
	}
	
	//分离一个标准URL地址中的参数，返回一个数组
	static function convertUrlQuery($query)
	{
		if(!$query) return false;
		$queryParts = explode('&', $query);
		$params = array();
		foreach ($queryParts as $param){
			$item = explode('=', $param);
			$item_0 = isset($item[0]) ? $item[0] : null;
			$item_1 = isset($item[1]) ? $item[1] : null;
			$params[$item_0] = $item_1;
		}
		return $params;
	}
	
	//把分类的URL参数合并成完整的URL，返回字符串
	static function getUrlQuery(array $array_query)
	{
		if(!$array_query) return false;
		$tmp = array();
		foreach($array_query as $k=>$param){
			$tmp[] = $k.'='.$param;
		}
		$params = implode('&',$tmp);
		return $params;
	}
	
	static function urlParams($url)
	{
		if(!$url) return false;
		$url = parse_url($url);
		$query = isset($url['query']) ? $url['query'] : null;
		$params = self::convertUrlQuery($query);
		return $params;
	}
	
	static function curlChangeIp($url)
	{
		if(!$url) return false;
		$ip = rand(1,255).".".rand(1,255).".".rand(1,255).".".rand(1,255)."";
		$header = array(
			"CLIENT-IP:$ip",
			"X-FORWARDED-FOR:$ip",
		);		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$content = curl_exec($ch);
		//var_dump(curl_getinfo($ch));
		curl_close($ch);
		return $content;
	}
	
	//if "text/json" data must array, else can be string.
	static function curlPost($url, $data=array(), $header=array())
	{
		if(!$url || !$data) return false;
		if("text/json" === $header) {
			$data = urldecode(json_encode($data));
			$header = array('Content-Type: application/json; charset=utf-8', 'Content-Length: '.strlen($data));
		}
		$ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		
		if ($ssl){
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		
		$content = curl_exec($ch);
		curl_close($ch);
	
		return $content;
	}
	
	static function curlGet($url)
	{
		if(!$url) return false;
		$ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
		
		//$header[] = "Content-type: text/xml";
		$header[] = false;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		if ($ssl){
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		$content = curl_exec($ch);
		curl_close($ch);
	
		return $content;
	}
	
	//利用file_get_contents POST数据
	//用法：$data = array('name'=>'test','email'=>'test@gmail.com'); Post('http://www.baidu.com', $data);
	static public function fgcPost($url, $post = null)
	{
		$context = array();
		if (is_array($post)){
			ksort($post);
			$context['http'] = array(
				'timeout'=>60,			//设置超时时间
				'method' => 'POST',
				'content' => http_build_query($post, '', '&'),
			);
		}
		return file_get_contents($url, false, stream_context_create($context));
	} 
	
	
	//限制显示的字符数，{$content|strip_tags|truncate_cn=460,'..',0}
	static function truncate_cn($string,$length=0,$ellipsis='…',$start=0)
	{
		$string=strip_tags($string);
		$string=preg_replace('/\n/is','',$string);
		//$string=preg_replace('/ |　/is','',$string);//清除字符串中的空格
		$string=preg_replace('/&nbsp;/is','',$string);
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/",$string,$string);
		if(is_array($string)&&!empty($string[0])){
			$string=implode('',$string[0]);
			if(strlen($string)<$start+1)return '';
			preg_match_all("/./su",$string,$ar);
			$string2='';
			$tstr='';
			for($i=0;isset($ar[0][$i]);$i++){
				if(strlen($tstr)<$start){
					$tstr.=$ar[0][$i];
				}else{
					if(strlen($string2)<$length+strlen($ar[0][$i])){
						$string2.=$ar[0][$i];
					}else{
						break;
					}
				}
			}
			return $string==$string2?$string2:$string2.$ellipsis;
		}else{
			$string='';
		}
		return $string;
	}
	
	//获取上一个月或者下一个月的时间
	static function getMonth($date, $flag='-1')
	{
		$time = strtotime("$date  $flag month");
		return array('zh'=>date("Y年m月",$time), 'date'=>date("Y-m",$time));
	}
	
	//字符串中查找数字。
	static function strFindNum($str)
	{
		$str=trim($str);
		if(empty($str)){return '';}
		$result='';
		for($i=0;$i<strlen($str);$i++){
			if(is_numeric($str[$i])){
				$result.=$str[$i];
			}
		}
		return (int)$result;
	}
	
	static function createDir($aimUrl) 
	{
		if(!$aimUrl) return false;
		$aimUrl = str_replace(' ', '/', $aimUrl);
		$aimDir = '';
		$arr = explode('/', $aimUrl);
		$result = true;
		foreach ($arr as $str) {
			$aimDir .= $str . '/';
			if (!file_exists($aimDir)) {
				$result = mkdir($aimDir);
			}
		}
		return $result;
	}
	
	static function downImage($url, $filename)
	{ 
		if(!$url || !$filename) return false; 
		if (file_exists($filename)) return $filename;
		
		ob_start(); 
		readfile($url); 
		$img = ob_get_contents(); 
		ob_end_clean(); 
		$size = strlen($img); 
		if($size < 5000) return false;	//排除无效请求情况.5K以内无效
		
		$fp2=@fopen($filename, "a"); 
		//$imageFile = stream_get_contents($fp2);
		fwrite($fp2,$img); 
		fclose($fp2); 
		return $filename; 
	}
	
	//分页栏显示第一页和最后一页，中间显示页数自定义
	static public function pagedShow($tatal_page, $page, $page_size, $shownum=9)
	{
		$tatal_page = (int)$tatal_page;
		$page = (int)$page;
		$page_size = (int)$page_size;
		$shownum = (int)$shownum;
		if($tatal_page < 1) $tatal_page = 1;
		if($page < 1) $page = 1;
		if($page > $tatal_page) $page = $tatal_page;
		if($page_size < 1) $page_size = 1;
		if($shownum < 3) $shownum = 3;
		if($shownum > $tatal_page) $shownum = $tatal_page;
		
		$arr = array();
		if($tatal_page<=$shownum){
			for($i=1; $i<=$shownum; $i++){
				$arr[] = $i;
			}
		}else if($page<=ceil($shownum/2) && $tatal_page>$shownum){
			for($i=1; $i<=$shownum; $i++){
				$arr[] = $i;
			}
			$arr[] = ">>";
			$arr[] = $tatal_page;
		}else if($page > ceil($shownum/2) && $page < ceil($tatal_page-ceil($shownum/2)) && $tatal_page>$shownum){
			$arr[] = 1;
			$arr[] = "<<";
			for($i=$page-floor($shownum/2); $i<=($page+floor($shownum/2)); $i++){
				$arr[] = $i;
			}
			$arr[] = ">>";
			$arr[] = $tatal_page;
		}else if(($page+ceil($shownum/2))>=$tatal_page && $tatal_page>$shownum){
			$arr[] = 1;
			$arr[] = "<<";
			for($i=$page-floor($shownum/2); $i<=$tatal_page; $i++){
				$arr[] = $i;
			}
		}
		return $arr;
	}
	
	//实现等比例不失真缩放
	/*
	 * $file 图片路径，比如 /tmp/1.jpg
	 * $maxwidth 定义生成图片的最大宽度（单位：像素）
	 * $maxheight 生成图片的最大高度（单位：像素）
	 * $name 生成的图片名
	 * $filetype 最终生成的图片类型（.jpg/.png/.gif）
	 * $watermark 是否添加水印 array('pathe'=>'水印路径','margin'=>'水印离的边距');
	 */
	static public function resizeImage($file, $maxwidth, $maxheight, $watermark=array(), $name='',$filetype='.jpg')
	{
		if($watermark){
			$im = Func::watermarkImage($file, $watermark['path'], $watermark['margin'], false);
			if(!$im) return false;
		}else{
			$ext = substr(strrchr($file, '.'), 1);
			switch ($ext){
				case 'png':		//进行透明处理
					$im = imagecreatefrompng($file);
					if(!$im) $im = imagecreatefromjpeg($file);
					if(!$im) $im = imagecreatefromgif($file);
					break;
				case 'gif':
					$im = imagecreatefromgif($file);
					if(!$im) $im = imagecreatefromjpeg($file);
					if(!$im) $im = imagecreatefrompng($file);
					break;
				case 'jpg':
				default:
					$im = imagecreatefromjpeg($file);
					if(!$im) $im = imagecreatefrompng($file);
					if(!$im) $im = imagecreatefromgif($file);
			}
			if(!$im) return false;
		}
		
		$maxwidth = (int)$maxwidth;
		$maxheight = (int)$maxheight;
		$pic_width = imagesx($im);
		$pic_height = imagesy($im);
		
		$resizewidth_tag = false;
		$resizeheight_tag = false;
		if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)){
			if($maxwidth && $pic_width > $maxwidth){
				$widthratio = $maxwidth/$pic_width;
				$resizewidth_tag = true;
			}	
			if($maxheight && $pic_height > $maxheight){
				$heightratio = $maxheight/$pic_height;
				$resizeheight_tag = true;
			}
			if($resizewidth_tag && $resizeheight_tag){
				if($widthratio<$heightratio)
					$ratio = $widthratio;
				else
					$ratio = $heightratio;
			}
			if($resizewidth_tag && !$resizeheight_tag)
				$ratio = $widthratio;
			if($resizeheight_tag && !$resizewidth_tag)
				$ratio = $heightratio;
	
			$newwidth = $pic_width * $ratio;
			$newheight = $pic_height * $ratio;
	
			if(function_exists("imagecopyresampled")){
				$newim = imagecreatetruecolor($newwidth,$newheight);
				imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
			}else{
				$newim = imagecreate($newwidth,$newheight);
				imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
			}
			if(!$name){
				imagejpeg($newim);
				return $newim;
			}else{
				$name = $name.$filetype;
				imagejpeg($newim,$name);
				imagedestroy($newim);
			}
		}else{
			if(!$name){
				imagejpeg($im);
				return $im;
			}else{
				$name = $name.$filetype;
				imagejpeg($im,$name);
				imagedestroy($im);
			}
		}
	}
	
	//实现上下剪切图片, $top 为剪切上部像数数, $bottom 为剪切底部像数数
	/*
	 * $file 图片路径，比如 /tmp/1.jpg
	* $top 去除上部像数数,（单位：像素）
	* $bottom 去除底部像数数（单位：像素）
	* $left 去除左边像数数（单位：像素）
	* $right 去除右边像数数（单位：像素）
	* $name 生成的图片名
	* $return_width_height:是否返回高度宽度，此时返回数组
	* $filetype 最终生成的图片类型（.jpg/.png/.gif）
	*/
	static public function cutImage($file, $top=0, $bottom=0, $left=0, $right=0, $newfile='', $return_width_height=0, $newfiletype='')
	{
		if(!$file || !file_exists($file)) return false;
		$top = (int)$top;
		$bottom = (int)$bottom;
		$left = (int)$left;
		$right = (int)$right;
		if($top<0) $top = 0;
		if($bottom<0) $bottom = 0;
		if($left<0) $left = 0;
		if($right<0) $right = 0;
		if(!$top && !$bottom && !$left && !$right) return $file;
		 
		$ext = substr(strrchr($file, '.'), 1);
		switch ($ext){
			case 'png':		//进行透明处理
				$im = imagecreatefrompng($file);
				if(!$im) $im = imagecreatefromjpeg($file);
				if(!$im) $im = imagecreatefromgif($file);
				break;
			case 'gif':
				$im = imagecreatefromgif($file);
				if(!$im) $im = imagecreatefromjpeg($file);
				if(!$im) $im = imagecreatefrompng($file);
				break;
			case 'jpg':
			default:
				$im = imagecreatefromjpeg($file);
				if(!$im) $im = imagecreatefrompng($file);
				if(!$im) $im = imagecreatefromgif($file);
		}
		if(!$im) return false;

		$pic_width = imagesx($im);
		$pic_height = imagesy($im);
		 
		$newwidth = $pic_width - $left - $right;
		$newheight = $pic_height - $top - $bottom;
		$newim = imagecreatetruecolor($newwidth, $newheight);
		if(!$newim) return false;

		imagecopy($newim, $im, 0, 0, $left, $top, $pic_width, $pic_height);
	
		if(!$newfile){
			imagejpeg($newim);
			imagedestroy($im);
			if(!$return_width_height) return $newim;
			return array('im'=>$newim, 'width'=>$newwidth, 'height'=>$newheight);
		}else{
			$newfile = $newfile.$newfiletype;
			imagejpeg($newim, $newfile);
			imagedestroy($newim);
			imagedestroy($im);
			if(!$return_width_height) return $newfile;
			return array('file'=>$newfile, 'width'=>$newwidth, 'height'=>$newheight);
		}
	
	}
	
	//实现图片居中添加水印
	/*
	 * $file 大图原图片路径，比如 /tmp/1.jpg
	* $water_file 水印图片地址：/tmp/water.jpg
	* $name 生成的图片名
	* $magrin 离顶部或底边距离
	* $return_width_height:是否返回高度宽度，此时返回数组
	* $filetype 最终生成的图片类型（.jpg/.png/.gif）
	*/
	static public function watermarkImage($file, $water_file, $magrin=0, $show=true, $newfile='', $return_width_height=0, $rand=0, $newfiletype='')
	{
		if(!$file || !file_exists($file) || !$water_file || !file_exists($water_file)) return false;
		$magrin = (int)$magrin;
		if($magrin < 0) $magrin=0;
	
		$ext = substr(strrchr($file, '.'), 1);
		switch ($ext){
            case 'png':     //进行透明处理
                $im = imagecreatefrompng($file);
                if(!$im) $im = imagecreatefromjpeg($file);
                if(!$im) $im = imagecreatefromgif($file);
                break;
            case 'gif':
                $im = imagecreatefromgif($file);
                if(!$im) $im = imagecreatefromjpeg($file);
                if(!$im) $im = imagecreatefrompng($file);
                break;
            case 'jpg':
            default:
                $im = imagecreatefromjpeg($file);
                if(!$im) $im = imagecreatefrompng($file);
                if(!$im) $im = imagecreatefromgif($file);
        }
        if(!$im) return false;

		$pic_width = imagesx($im);
		$pic_height = imagesy($im);
	
		$ext = substr(strrchr($water_file, '.'), 1);
		switch ($ext){
			case 'png':		//进行透明处理
				$wim = imagecreatefrompng($water_file);
				imagealphablending($wim,false);
				imagesavealpha($wim,true);
				break;
			case 'gif':
				$wim = imagecreatefromgif($water_file);
				break;
			case 'jpg':
			default:
				$wim = imagecreatefromjpeg($water_file);
		}
		if(!$wim) return false;		

		$wwidth = imagesx($wim);
		$wheight = imagesy($wim);
		if($wwidth > $pic_width || $wheight > ($pic_height-$magrin)) return false;
	
		//$dst_x = $pic_width/2 - $wwidth/2;		//居中
		//$dst_x = 10;								//靠左
		$dst_x = $pic_width - $wwidth - 10;			//靠右
		
		$dst_y = $pic_height - $wheight - $magrin;
		if($rand){
			$rand = rand(0, 1);
			if(1==$rand){
				$dst_x = 0;
				$dst_y = $magrin;
			}
		}
		imagecopy($im, $wim, $dst_x, $dst_y, 0, 0, $wwidth, $wheight);
	
		if(!$newfile){
			imagedestroy($wim);
			if(!$show) return $im;
			imagejpeg($im);
			if(!$return_width_height) return $im;
			return array('im'=>$im, 'width'=>$pic_width, 'height'=>$pic_height);
		}else{
			$newfile = $newfile.$newfiletype;
			imagejpeg($im, $newfile);
			imagedestroy($im);
			imagedestroy($wim);
			if(!$return_width_height) return $newfile;
			return array('file'=>$newfile, 'width'=>$pic_width, 'height'=>$pic_height);
		}
	
	}
	
	/*
	 * 功能：为多维数组的键或名添加额外的字符串
	 * 用法：arrayAddStr($data);
	 */
	static public function arrayAddStr(array $data, $key_str='', $value_str='')
	{
		$tmp = array();
		foreach ($data as $k => $v){
			$tmp[$k.$key_str] = is_array($v) ? self::arrayAddStr($v) : $v.$value_str;
		}
		return $tmp;
	}

	//分离出来的加密和解密方法，2个方法的合体与encodeStr方法同效果,但比之用了2层MD5加密和增加了一道密钥key验证。
	static public function encode($tex, $key = "key123@ison")
	{
		$chrArr = array(
				'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
				'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
				'0','1','2','3','4','5','6','7','8','9'
		);
		$key_b = $chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62];
		$rand_key = $key_b.$key;
		$rand_key=md5(md5($rand_key).$key_b);
		$key_p  = substr($rand_key, 0, 10);
		
		$texlen=strlen($tex);
		$reslutstr="";
		for($i=0;$i<$texlen;$i++){
			$reslutstr.=$tex{$i}^$rand_key{$i%32};
		}
		$reslutstr=trim($key_b.base64_encode($key_p.$reslutstr),"==");
		$reslutstr=substr(md5($reslutstr), 0,8).$reslutstr;
		return $reslutstr;
	}
	static public function decode($tex, $key = "key123@ison")
	{
		if(strlen($tex)<24)return false;
		$verity_str=substr($tex, 0,8);
		$tex=substr($tex, 8);
		if($verity_str!=substr(md5($tex),0,8)) return false;  //完整性验证失败
	
		$key_b = substr($tex,0,6);
		$rand_key = $key_b.$key;
		$rand_key=md5(md5($rand_key).$key_b);
		$key_p  = substr($rand_key, 0, 10);
		
		$texs=base64_decode(substr($tex, 6));
		$kp = substr($texs, 0,10);
		if($key_p !== $kp) return false;	//$key检验
		
		$tex = substr($texs, 10);
		$texlen=strlen($tex);
		$reslutstr="";
		for($i=0;$i<$texlen;$i++){
			$reslutstr.=$tex{$i}^$rand_key{$i%32};
		}
		return $reslutstr;
	}
	//-------- end 加密
	
	//检查是否手机号
	static public function checkmobile($str)
	{
		$pattern = "/^13[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$/";
		if (preg_match($pattern,$str)) return true;
		return false;
	}
	
	//-------判断是否在微信中打开页面
	static public function isInWeixin()
	{
		$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		if(strpos($useragent, 'MicroMessenger') !== false){
			return true;
		}
		return false;
	}
	
	//检查是否email
	static public function checkEmail($email)
	{
		$is_email = preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$email);
		return $is_email;
	}
	
}
