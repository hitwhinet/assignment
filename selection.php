<?php
//载入项目初始化脚本
require './init.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

//定义储存用户数据的文件路径
$file_path = "./data/$user_id.txt";

//判断文件是否存在
if(is_file($file_path)){
	//从文件中取出用户数据并反序列化
	$user_data = unserialize(file_get_contents($file_path));
}

//载入修改用户信息的页面文件
require './view/selection.html';