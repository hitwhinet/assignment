<?php
//载入项目初始化脚本
require './init.php';
require './mysql.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

$clstime = array(1,2,3,4,5,6,7,8);//存储选课信息，用于显示“第几节”的下拉菜单
$teacher = $_SESSION['user'];

if($_POST){
	//$success = true;//若设置此变量，则页面显示“保存成功”字样
	$class_id = 1;
	//如果在post请求中提交了clstime 则获取
	$user_data = isset($_POST['clstime']) ? $_POST['clstime'] : '';
	$sql = "select * from appoint_t1 where user_t = '$teacher' and time_t ='$user_data'";
	$res = $mysqli->query($sql);
	if(!$res) {
		die("sql error:\n".$mysqli->error);
	}
	$num = mysqli_num_rows($res);
	$res->free();	
	if(!$num)
	{
		$sql = "insert into appoint_t1 value('$teacher','$user_data',0)";
		$res = $mysqli->query($sql);
		if(!$res) {
			die("sql error:\n".$mysqli->error);
		}
		$mysqli->close();
		echo "<script>alert('闲时设置成功')</script>"; 
	}
	
	else
	{
		echo "<script>alert('该闲时已设置')</script>";
	}
}

//先判断提交的时间id的状态
//是空闲时间时，改变数据库中的相应id值

//载入显示用户信息的页面文件
require './view/settime.html';
