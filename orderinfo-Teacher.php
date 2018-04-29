<?php
//载入项目初始化脚本
require './init.php';
require './mysql.php';
//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

//orderinfo 显示学生向教师预约的信息
//格式：时间，节次，学生学号，学生所在学院，学生姓名
$orderinfo = array(array());
$user_t = $_SESSION['user']; 
$date_t = date('Y/m/d',strtotime("+1 day"));

$sql = "select s1.time_t,s1.user_s,class,name_s from info_s s,appoint_s1 s1,appoint_t1 t1 where t1.user_t='$user_t' and t1.appoint = 1 and t1.user_t = s1.user_t and s1.time_t = t1.time_t and s1.user_s = s.user_s ";

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
        $i++;
}

$res->free();

//载入查询学生预约情况的页面文件
require './view/orderinfo-Teacher.html';
