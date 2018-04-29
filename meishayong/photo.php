<?php
//载入项目初始化脚本
require './init.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

//根据用户id拼接头像文件保存路径
$save_path = "./uploads/photo/thumb_$user_id.jpg";

//判断是否上传头像
if(isset($_FILES['pic'])){
	//获取用户上传文件信息
	$pic = $_FILES['pic'];
 	//判断文件上传到临时文件时是否出错
	checkUpload($pic);
	//判断是否为合法的图片文件类型
	checkUploadPhoto($pic);
	//验证成功，为头像生成缩略图
	thumb(150,150,$pic['tmp_name'],$save_path);
}

//载入HTML模板文件
require './view/photo.html';