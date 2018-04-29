<?php
//载入项目初始化脚本
require './init.php';
require './mysql.php';
//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录


//orderinfo数组：学生预约教师的情况
//格式：日期，节次，教师姓名，教师院系，教师办公室，联系电话
$orderinfo = array(array());
$user_s = $_SESSION['user'];
$date_t = date('Y/m/d',strtotime("+1 day"));

$sql = "select s1.time_t,name_t,dept,office,tel from info_t t,appoint_t1 t1,appoint_s1 s1 where s1.user_s = '$user_s' and s1.user_t  = t1.user_t and s1.time_t = t1.time_t and t1.user_t = t.user_t ";
$res = $mysqli->query($sql); 
if (!$res) { 
        die("sql error:\n". $mysqli->error); 
} 
$i = 0;
while($row = $res->fetch_array()){ 
	$orderinfo[$i][0] = $date_t;
	$orderinfo[$i][1] = $row[0];
	$orderinfo[$i][2] = $row[1];
	$orderinfo[$i][3] = $row[2];
	$orderinfo[$i][4] = $row[3];
	$orderinfo[$i][5] = $row[4];
	$i++;
}
$res->free();
  

//载入查询学生预约情况的页面文件
require './view/orderinfo-Student.html';
