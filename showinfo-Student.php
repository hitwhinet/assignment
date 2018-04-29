<?php
//载入项目初始化脚本
require './init.php';
require './mysql.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

/*
此处连接数据库 读取学生信息
 */

$username = $_SESSION['user'];
$sql = "select * from info_s where user_s = '$username'";
$res = $mysqli->query($sql);
if(!$res) {
	die("sql error:\n".$mysqli->error);
}

$user_data = $res->fetch_array();
$res->free();
$mysqli->close();
/*
echo $user_data[0];
echo $user_data[1];
echo $user_data[2];
*/
//载入修改用户信息的页面文件
require './view/showinfo-Student.html';
