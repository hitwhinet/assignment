<?php
//载入项目初始化脚本
require './init.php';
require './mysql.php';

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
	/*$user_data = getUserData();
	$link = mysql_connect('localhost', 'root', 'hitimc@wh');
	mysql_query('set names utf8');
	$re = mysql_select_db('usr_s', $link);
	$sql = "select * from info_s where user_s = '$username' and pwd_s = '$password'";
	$result = mysql_query($sql);
	$rows = mysql_fetch_assoc($result);
*/
	$password = md5($password);
	$sql = "select user_s,pwd_s from user_s where user_s = '$username ' and pwd_s = '$password';";
	$res = $mysqli->query($sql);
	if($res->num_rows) {
		$_SESSION['user'] = $username;
		header('Location: selection.php');
		exit;
	}
	error('登陆失败! 用户名或密码错误，请刷新页面重试');
}

//没有提交表单时，载入登录页面
require './view/login.html';
