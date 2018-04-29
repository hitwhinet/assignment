<?php
//载入初始化脚本
require './init.php';
require './mysql.php';

//判断是否有表单提交
if ($_POST)
{
	//接收表单字段
         $username = isset($_POST['username']) ? trim($_POST['username']) : '';
         $password = isset($_POST['password']) ? $_POST['password'] : '';
         $captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';
	$id ='0';//老师id=1 学生id=0
	//判断验证码
	//echo $_POST['id'];
	checkCaptcha($captcha);
	$password = md5($password);
 
	if($_POST['id'] == $id )
	{
		$sql = "select * from user_s where user_s = '$username' and pwd_s = '$password' ;";
	}
	else
	{
		$sql = "select * from user_t where user_t = '$username' and pwd_t = '$password' ;";
	}
	$res = $mysqli->query($sql);
	if ($res->num_rows > 0)
	{
		//用户名密码匹配
		//开启session会话，将学号放入session中
		$_SESSION['user'] = $username;
		$_SESSION['id']= $_POST['id'];
		$res->free();
		$mysqli->close();
		//重定向到用户信息页面
		if($id == $_POST['id'])
		{
			header('Location:showinfo-Student.php');
		}
		else
		{
			header('Location:showinfo-Teacher.php');
		}	
		exit;
	}
	error( '用户名密码错误');
}

require './view/login.html';
