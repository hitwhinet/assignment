<?php

//判断用户是否登录
function checkLogin(){
	//当用户没有登录时，重定向到登录页面
	if(!isset($_SESSION['user'])){
		header('Location: login_l.php'); 
		exit; //停止脚本文件继续执行
	}
	//用户已登录，返回用户ID
	return isset($_SESSION['user']['id']) ? $_SESSION['user']['id']: 0;
}

//判断验证码
function checkCaptcha($captcha){
	//判断验证码
	if(empty($_SESSION['captcha'])){  //如果Session中不存在验证码，则退出
		error('验证码已经过期，请刷新页面重试。');
	}
	//获取验证码并清除Session中的验证码
	$true_captcha = $_SESSION['captcha'];
	unset($_SESSION['captcha']); //限制验证码只能验证一次，防止重复利用
	//忽略字符串的大小写，进行比较
	if(strtolower($captcha) !== strtolower($true_captcha)){
		error('您输入的验证码不正确！请刷新页面重试。');
	}
}

//获取用户数据
function getUserData(){
	//通过静态变量保存用户数据
	static $user_data;
	//如果没有载入用户数据，则先进行载入
	if(!$user_data){
		$user_data = require './data/data.php';
	}
	return $user_data;
}

//判断上传是否成功
function checkUpload($file){
	if($file['error'] > 0){
		$error = '上传失败：';
		switch($file['error']){
 			case 1: $error .= '文件大小超过了服务器设置的限制！';break;
			case 2: $error .= '文件大小超过了表单设置的限制！'; break;
			case 3: $error .= '文件只有部分被上传！'; break;
			case 4: $error .= '没有文件被上传！'; break;
			case 6: $error .= '上传文件临时目录不存在！'; break;
			case 7: $error .= '文件写入失败！'; break;
			default: $error .='未知错误！'; break; 
		}
		error($error);  //显示错误信息并停止脚本
	}
}

//判断文件类型
function checkUploadPhoto($file){
	//判断是否为允许的图片格式
	$type = strrchr($file['name'],'.');
	if(($type !== '.jpg') || ($file['type'] !== 'image/jpeg')){
		error('图像类型不符合要求，只支持jpg类型的图片');
	}
}

//为上传头像生成缩略图
function thumb($max_width,$max_height,$file_path,$save_path){
	list($width, $height) = getimagesize($file_path);
	//等比例计算缩略图的宽和高
	if($width/$max_width > $height/$max_height) {
		//宽度大于高度时，将宽度限制为最大宽度，然后计算高度值
		$new_width = $max_width;
		$new_height = round($new_width / $width * $height);
	}else{
		//高度大于宽度时，将高度限制为最大高度，然后计算宽度值
		$new_height = $max_height;
		$new_width = round($new_height / $height * $width);
	}
	//绘制缩略图的画布资源
	$thumb = imagecreatetruecolor($new_width, $new_height);
	//从文件中读取出图像，创建为jpeg格式的图像资源
	$source = imagecreatefromjpeg($file_path);
	//将原图缩放填充到缩略图画布中
	imagecopyresized($thumb,$source,0,0,0,0,$new_width,$new_height,$width,$height);
	//将保存缩略图到指定目录（参数依次为图像资源、保存目录、输出质量0~100）
	imagejpeg($thumb, $save_path, 100);
}

//提示错误并退出
function error($msg){
	exit('<script>alert("'.htmlspecialchars($msg).'");history.back();</script>');
}

/**
 * 添加水印功能
 * @param string $source 原图
 * @param string $water 水印图片
 * @param int $postion 添加水印位置，1表示左上角
 * @param string $path 水印图片存放路径，默认为空，表示在当前目录
 */
function watermark($source, $water, $postion=1, $path=''){
	//设置水印图片名称前缀
	$waterPrefix = 'water_';
	//图片类型和对应创建画布资源的函数名
	$from = array(
		'image/gif'  => 'imagecreatefromgif',
		'image/png'  => 'imagecreatefrompng',
		'image/jpeg' => 'imagecreatefromjpeg'
	);
	//图片类型和对应生成图片的函数名
	$to = array(
		'image/gif'  => 'imagegif',
		'image/png'  => 'imagepng',
		'image/jpeg' => 'imagejpeg'
	);

	//获取原图和水印图片信息
	$src_info = getimagesize($source);
	$water_info = getimagesize($water);

	//从数组中获取原图和水印图片的宽和高
	list($src_w, $src_h,$src_mime) = $src_info;
	list($wat_w, $wat_h,$wat_mime) = $water_info;

	//获取各图片对应的创建画布函数名
	$src_create_fname = $from[$src_info['mime']];
	$wat_create_fname = $from[$water_info['mime']];

	//使用可变函数来创建画布资源
	$src_img = $src_create_fname($source); 
	$wat_img = $wat_create_fname($water);
	//水印位置
	switch ($postion) {
		case 1: //左上
			$src_x = 0;
			$src_y = 0;
			break;	
		case 2: //右上
			$src_x = $src_w - $wat_w;
			$src_y = 0;
			break;	
		case 3: //中间
			$src_x = ($src_w - $wat_w)/2;
			$src_y = ($src_h - $wat_h)/2;
			break;	
		case 4: //左下
			$src_x = 0;
			$src_y = $src_h - $wat_h;
			break;	
		default: //右下
			$src_x = $src_w - $wat_w;
			$src_y = $src_h - $wat_h;
			break;	
	}
	
	/**
	  * 生成水印方式一：将水印图片添加到目标图标上
	  * @param resource $src_img 原图像资源
	  * @param resource $wat_img 水印图像资源
	  * @param int $src_x  水印图片在原图像中的横坐标
	  * @param int $src_y  水印图片在原图像中的纵坐标
	  * @param int 0, 0    水印图片的横坐标和纵坐标
	  * @param int $wat_w  水印图片的宽
	  * @param int $wat_h  水印图片的高
	  */
	imagecopy($src_img, $wat_img, $src_x, $src_y, 0, 0, $wat_w, $wat_h);

	//生成水印方式二：使用imagecopymerge()函数设置半透明水印
	//最后一个参数为透明度，取值范围0~100
	//imagecopymerge($src_img, $wat_img, $src_x, $src_y, 0, 0, $wat_w, $wat_h, 50);
	
	//生成带水印的图片路径
	$waterfile = $path.$waterPrefix.basename($source);
	//获取输出图片格式的函数名
	$generate_fname = $to[$src_info['mime']];
	//判断将添加水印后的图片输出到指定目录是否正确
	if($generate_fname($src_img,$waterfile)){
		return $waterfile;
	}else{
		return false;
	}
}
