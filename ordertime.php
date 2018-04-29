<?php
//载入项目初始化脚本
require './init.php';
require './mysql.php';
//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

if($_POST){
	//header('Location: ordertime.php');
	$date_t = date('Y/m/d',strtotime("+1 day"));
	$teachername = $_POST['name'];//获取POST提交的教师姓名
	$sql = "select a.time_t,i.dept,i.office,i.tel from appoint_t1 a,info_t i where name_t = '$teachername' and appoint = 0";
	$res = $mysqli->query($sql);
	if (!$res) {
		die("sql error:\n". $mysqli->error);
	}
	$i = 0;
	$freetime = array(array());
	/*将内容逐条写入二维数组中*/
	while($row = $res->fetch_array()){
		$freetime[$i][0] = $date_t;
		$freetime[$i][1] = $row[0];
		$freetime[$i][2] = $teachername;
		$freetime[$i][3] = $row[1];
		$freetime[$i][4] = $row[2];
		$freetime[$i][5] = $row[3];
		$i++;
	}
}

//响应“我们约起~”链接发起的GET请求
if($_GET){
	//获取GET请求提交的日期date, 节次clsid，教师姓名tname
	$date = isset($_GET['date'])?$_GET['date']:"";
	$clsid = isset($_GET['clsid'])?$_GET['clsid']:"";
	$tname = isset($_GET['tname'])?$_GET['tname']:"";

	/*执行数据库操作*/
	$user_s = $_SESSION['user'];
	/*查看学生这节课是否空闲*/
	$sql = "select * from appoint_s1 where user_s = '$user_s' and time_t = '$clsid'";
	$res =  $mysqli->query($sql);
	if (!$res) {
		die("sql error:\n". $mysqli->error);
	}
	$result = $res->fetch_array();
	if( !$result[0])
	{
		$res->free();
		
		/*查询老师职工号*/
		$sql = "select user_t from info_t where name_t ='$tname'";
		$res =  $mysqli->query($sql);
		if (!$res) {
			die("sql error:\n". $mysqli->error);
		}
		$user_t = $res->fetch_array();
		$res->free();

		/*修改老师时间为已预约*/
		$sql = "update appoint_t1 set appoint = 1 where user_t = '$user_t[0]' and time_t = '$clsid'";
		$res = $mysqli->query($sql);
		if (!$res) {
			die("sql error:\n". $mysqli->error);
		}
		
		/*向学生预约表插入信息*/
		$sql = " insert into appoint_s1(user_s,user_t,time_t) values('$user_s','$user_t[0]','$clsid')";
		$res = $mysqli->query($sql);
		if (!$res) {
			die("sql error:\n". $mysqli->error);
		}

		$orderresult = $date." 第".$clsid."节，老师：".$tname;
		//$orderresult = "预约成功~\n".$date." 第".$clsid."节，老师：".$tname;		
		echo "<script> alert('预约成功~'+'\\n'+'{$orderresult}') </script>";//卧槽 换行需要两个反斜线！！！
		//header('Location: ordertime.php');
	}
	else
	{
		echo "<script> alert('该老师尚未设置空闲时间...') </script>";
		header('Location: ordertime.php');
	}
}

//载入查询教师闲时、预约时间的页面文件 
require './view/ordertime.html';
