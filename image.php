<?php

$img_h = 600;

$img_w = 800;

$img = imagecreate($img_w, $img_h);

imagecolorallocate($img, 195, 190, 212);

$count = 4;

$charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ23456789';

$charset_len = strlen($charset) - 1;

$code = '';

for($i = 0; $i < $count; ++$i){
	$code.=$charset[mt_rand(0,$charset_len)];
}

session_start();

$_SESSION['captcha'] = $code;

echo $code;

//在画布中绘制验证码文本
$fontSize = 16;    //文字大小
$fontStyle = 'G:/WEB/WWW/test/fonts/SourceCodePro-Bold.ttf'; //字体样式
//生成指定长度的验证码
for($i=0; $i<$count; ++$i){
    //随机生成字体颜色
    $fontColor = imagecolorallocate($img,mt_rand(0,100),mt_rand(0,50),mt_rand(0,255));
    imagettftext (
	    $img,        //画布资源
         $fontSize,  //文字大小
	    mt_rand(0,20) - mt_rand(0,25),            //随机设置文字倾斜角度
	    $fontSize*$i+20,mt_rand($img_h/2,$img_h), //随机设置文字坐标，并自动计算间距
	    $fontColor,  //文字颜色
	    $fontStyle,  //文字字体
	    $code[$i] 	 //文字内容
	);
}

//header('Content-Type: image/gif');

//imagegif($img);