<?php
//载入项目初始化脚本
require './init.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

//定义当前用户相册的顶级目录
$album_path = "./uploads/album-$user_id";

//如果不存在则创建目录
is_dir($album_path) || mkdir($album_path,0777,true);

//判断是否请求子相册的路径
if(isset($_GET['path'])){
	//获取用户请求的路径（过滤字符串两端的斜线、点等特殊字符）
	$path = trim($_GET['path'],'/\\.');
	//通过正则表达式，限制输入路径只允许字母、数字、下划线、斜线
	preg_match('/^[\w\/]*$/',$path) || error('路径只允许字母、数字、下划线、斜线');
	//将相册目录与请求目录拼接
	$path = $path ? "$album_path/$path" : $album_path;
	//判断路径是否合法
	is_dir($path) ||  error('您访问的相册不存在！');
}else{
	//默认使用相册目录作为请求目录
	$path = $album_path;
}

//实现相册创建
if(isset($_POST['dir_name'])){
	$dir_name = $_POST['dir_name'];
	//对相册名进行正则表达式匹配
	preg_match('/^\w+$/',$dir_name) || error('相册名只允许字母、数字、下划线');
	$target_path = "$path/$dir_name"; //拼接目标路径
	if(!file_exists($target_path)){
		mkdir($target_path, 0777); //如果文件不存在，创建目录
	}
}

//判断是否有文件上传
if(isset($_FILES['file_name'])){
	$pic = $_FILES['file_name'];
	//判断是否上传成功，如果失败则提示错误信息
	checkUpload($pic);
	//为上传文件重新生成文件名
	$save_name = md5(uniqid(rand())).'.jpg';
	//拼接文件保存路径
	$save_path = "$path/$save_name";
	if(!move_uploaded_file($pic['tmp_name'],$save_path)){
		error('上传图片保存失败。');
	}
}

//获取文件列表
$folderlist = array();  //保存目录列表
$filelist = array();    //保存文件列表
$album_path_len = strlen($album_path)+1; //获取顶级相册路径长度
//解析目录
foreach(glob($path.'/*') as $v){
	if(is_dir($v)){
		//取出目录列表，并去掉前面的相册路径
		$folderlist[] = substr($v,$album_path_len);
	}elseif(is_file($v)){
		//取出文件列表
		$filelist[] = $v;
	}
}
//去除前面的相册路径
$path = substr($path,$album_path_len);

//载入HTML模板文件
require './view/album.html';