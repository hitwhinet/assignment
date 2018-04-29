<?php
//载入项目初始化脚本
require './init.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

//定义当前用户相册的顶级目录
$album_path = "./uploads/album-$user_id";
//如果不存在则创建目录
is_dir($album_path) || mkdir($album_path,0777,true);

//获取请求相册路径
$path = isset($_GET['path']) ? $_GET['path'] : '';
//从请求路径中提取出文件名和路径
$file = basename($path);
$path = dirname($path);

//过滤字符串两端的斜线、点等特殊字符
$path = trim($path,'/\\.');

//通过正则表达式，限制输入路径只允许字母、数字、下划线、斜线
preg_match('/^[\w\/]*$/',$path) || exit('路径只允许字母、数字、下划线、斜线');
//限制输入文件名只允许字母、数字、下划线、点
preg_match('/^[\w\.]*$/',$file) || exit('文件名只允许字母、数字、下划线、点');

//将相册目录与请求目录拼接
$path = $path ? "$album_path/$path" : $album_path;
//判断路径是否合法
is_dir($path) ||  exit('您访问的相册不存在！');

//将文件名拼接到路径中
$current = "$path/$file";
//判断文件名是否合法
is_file($current) || exit('您访问的图片不存在！');

//遍历文件列表
$file_list = glob("$path/*.*"); //获取文件所在目录的文件列表
foreach($file_list as $k=>$v){
	if($v == $current){
		//保存上一张、下一张图片
		$prev = isset($file_list[$k-1]) ? $file_list[$k-1] : '';
		$next = isset($file_list[$k+1]) ? $file_list[$k+1] : '';
		break; //停止循环
	}
}

//浏览历史功能

//清除历史记录
if(isset($_GET['action'])){
	if($_GET['action'] == 'clear'){
		unset($_COOKIE['history']);     //清除历史记录
		setcookie('history','',time()-1);  //清除COOKIE
	}
}

//判断Cookie中是否存在history记录
if(isset($_COOKIE['history'])){
	//获取Cookie，将字符串分割成数组，限制数组最多只能有5个元素
	$history = explode('|',$_COOKIE['history'],5);
	//遍历数组
	foreach($history as $k=>$v){
		//如果当前图片的路径在数组中已经存在，则删除
		if($v == $current) unset($history[$k]);
	}
	//当数组元素达到5个时，删除第1个元素
	if(count($history) >= 5) array_shift($history);
	//将当前访问的图片路径添加到数组末尾
	$history[] = $current;
	//将数组转换为字符串，重新保存到Cookie中
	setcookie('history',implode('|',$history));
}else{
	$history = array($current); //保存当前浏览图片到数组
	setcookie('history', $current); //将当前浏览图片保存到Cookie中
}

//获取顶级相册路径长度
$album_path_len = strlen($album_path)+1;
//去除前面的相册路径
$path = substr($path, $album_path_len); 

//载入HTML模板
require './view/album_show.html';