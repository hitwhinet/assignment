<?php
//载入项目初始化脚本
require './init.php';
require './mysql.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

if($_POST)
{
	//获取原密码oldpwd, 新密码newpwd（新密码与再次输入新密码的一致性已通过js检查）
	$oldpwd = isset($_POST['oldpwd']) ? $_POST['oldpwd'] : '';
	$newpwd = isset($_POST['newpwd']) ? $_POST['newpwd'] : '';
	
	//通过两次弹窗显示一下获取到的密码作为测试 直接删掉即可
	echo "<script> alert('{$oldpwd}') </script>";
	echo "<script> alert('{$newpwd}') </script>";
	


}


//载入修改用户信息的页面文件
require './view/resetpwd-Student.html';
