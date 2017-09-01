<?php
/**
 * Created by PhpStorm.
 * User:Micheal Xiao
 * Date:2017/8/31
 * Time:上午10:22
 * Caption:中文验证码
 */
function verify_chinese($width = 120,$height = 40,$num = 4,$type = 1){
    //1、创建图像
    $image = imagecreatetruecolor($width,$height);
    //2、画一个正方形,背景浅色系
    imagefilledrectangle($image,0,0,$width,$height,lightColor($image));
    //3、根据类型
    $temp = createTempStr($type);
    //4、开始写字\干扰点
    writeStr($temp,$image,$width,$height,$num);
    header("Content-type:image/png");
    imagepng($image);
    imagedestroy($image);
    return join('',$temp);
}

function writeStr($temp,$image,$width,$height,$num){
	for ($i=0; $i < count($temp); $i++) { 
		imagettftext($image, 18, mt_rand(-10,10), floor($width/$num*$i)+5, mt_rand(25,$height-10), darkColor($image), "Li-Xuke.ttf", $temp[$i]);
	}
	//干扰点
	for ($i=0; $i < 50; $i++) { 
		imagesetpixel($image, mt_rand(0,$width), mt_rand(0,$height), darkColor($image));
	}
}

function createTempStr($type){
    $string = '';
	$temp = array();
	switch ($type) {
		case 1:
		    $file = 'config_1.txt';
		    $content = file_get_contents($file);
		    $arr = explode(",",$content);
			//将数组打乱
			shuffle($arr);
			//截取
			$temp = array_slice($arr, 0,4);
			break;
		
		case 2:
            $file = 'config_2.txt';
            $content = file_get_contents($file);
            $arr = explode(",",$content);
			shuffle($arr);
			$tem = array_slice($arr, 0,1);
			$string = join('',$tem);
			$temp = char2arr($string);
			break;
	}
	return $temp;
}

function char2arr($str){
	$length = mb_strlen($str,'utf-8');
	$array = [];
	for ($i=0; $i < $length; $i++) { 
		$array[] = mb_substr($str, $i,1,'utf-8');
	}
    return $array;
}

function lightColor($image){
    return imagecolorallocate($image,mt_rand(130,255),mt_rand(130,255),mt_rand(130,255));
}

function darkColor($image){
    return imagecolorallocate($image,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120));
}
verify_chinese();