<?php
//载入项目初始化脚本
require './init.php';

//判断是否有登录表单提交
if($_POST){
	//接收表单字段
	$username = isset($_POST['username']) ? trim($_POST['username']) : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';
	//判断验证码
	checkCaptcha($captcha);
	//将用户名转换为小写
	$username = strtolower($username);
	//获取用户数据
	$user_data = getUserData();
    //到用户数组中验证用户名和密码
    foreach($user_data as $k=>$v){
        if($v['username'] == $username && $v['password'] == $password){
			//开启Session会话，将用户ID和用户名保存到Session中
			$_SESSION['user'] = array('id'=>$k, 'username'=>$v['username']);
			//重定向到用户中心个人信息页面
			header('Location: selection.php');
			exit;  //重定向后停止脚本继续执行
        }
    }
	error('登录失败！用户名或密码错误，请刷新页面重试。');  //验证失败
}

//没有提交表单时，载入登录页面
require './view/login.html';
