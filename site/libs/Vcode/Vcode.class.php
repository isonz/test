<?php
/*
 * 用法 $image = Vcode::show();
 * header("Content-type: image/png");
 * $image = imagePng($image);
 * imagedestroy($image);
 * exit;
 */

class Vcode
{
	private static $_image = null;
	private static $_code = null;
	private static $width = 100; 				//图片宽
	private static $height = 30;				//图片高
	private static $len = 4;					//生成几位验证码
	private static $bgcolor = "#eeeeee"; 		//背景色
	private static $noise = true;				//生成杂点
	private static $noisenum = 1000;			//杂点数量
	private static $border = false;				//边框
	private static $bordercolor = "#000000";
	
	
	static public function setCode($vcode)
	{
		if(!session_id()) session_start();
		$_SESSION['vcode_vc01'] = $vcode;
	}
	
	static public function unCode()
	{
		$vcode_vc01 = isset($_SESSION['vcode_vc01']) ? $_SESSION['vcode_vc01'] : null;
		if($vcode_vc01)	unset($_SESSION['vcode_vc01']);
	}
	
	//验证数据时使用此方法
	static public function getCode()
	{
		$vcode_vc01 = isset($_SESSION['vcode_vc01']) ? $_SESSION['vcode_vc01'] : null;
		return $vcode_vc01;
	}
	
	// 显示图片时使用此方法即可
	static public function show()
	{
		$width = self::$width;
		$height = self::$height;
		$len = self::$len;
		$bgcolor = self::$bgcolor;
		$noise = self::$noise;
		$noisenum = self::$noisenum;
		$border = self::$border;
		$bordercolor = self::$bordercolor;
		
		self::$_image = imageCreate($width, $height);
		$back = self::getcolor($bgcolor);
		imageFilledRectangle(self::$_image, 0, 0, $width, $height, $back);
		$size = $width/$len;
		if($size>$height) $size=$height;
		$left = ($width-$len*($size+$size/10))/$size;
		for ($i=0; $i<$len; $i++){
		    $randtext = self::vchar();
		    self::$_code .= $randtext;
			$textColor = imageColorAllocate(self::$_image, rand(0, 100), rand(0, 100), rand(0, 100));
			$font =  dirname(__FILE__)."/3.ttf"; 
			$randsize = rand($size-$size/5, $size-$size/10);
			$location = $left+($i*$size+$size/10);
			imagettftext(self::$_image, $randsize, rand(-18,18), $location, rand($size-$size/100, $size+$size/100), $textColor, $font, $randtext); 
		}
		if($noise == true) self::setnoise();
		
		self::setCode(self::$_code) ;
		
		//setrawcookie("code",$code,time()+3600,"/");
		//setcookie("code", $code, time()+120);  

		$bordercolor = self::getcolor($bordercolor); 
		if($border==true) imageRectangle(self::$_image, 0, 0, $width-1, $height-1, $bordercolor);
		return self::$_image;
	}
	
	static function getcolor($color)
	{
		$color = str_replace('#','',$color);
		$r = $color[0].$color[1];
	    $r = hexdec ($r);
		$b = $color[2].$color[3];
		$b = hexdec ($b);
		$g = $color[4].$color[5];
		$g = hexdec ($g);
		$color = imagecolorallocate (self::$_image, $r, $b, $g); 
		return $color;
	}

	static function setnoise()
	{
		for ($i=0; $i<self::$noisenum; $i++){
			$randColor = imageColorAllocate(self::$_image, rand(0, 255), rand(0, 255), rand(0, 255));  
			imageSetPixel(self::$_image, rand(0, self::$width), rand(0, self::$height), $randColor);
		} 
	}
	
	static function vchar()
	{
		global $ss,$tt;
		$ss=array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'3','4','5','6','7','8','9'
		);
		$tt=$ss[rand(0, 58)]; 
		return $tt;
	}
}
