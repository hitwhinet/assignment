<?php
$mysql_conf = array(
    'host'    => 'localhost', 
    'db'      => 'appoint', 
    'db_user' => 'root', 
    'db_pwd'  => 'hitimc@wh', 
    );

$mysqli=@new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
if( $mysqli->connect_errno )
{
	die("Could not connect:". $mysqli->connect_error);
}
//echo '数据库连接成功！';
$mysqli->query("set names 'utf8';");
$select_db = $mysqli->select_db($mysql_conf['db']);
if (!$select_db) {
    die("could not connect to the db:\n" .  $mysqli->error);
}
$sql="select * from info_s;";
$res = $mysqli->query($sql);
if (!$res) {
	die("sql error:\n". $mysqli->error);
}
while($row = $res->fetch_array()){
    echo $row[0];
}
$res->free();
$mysqli->close();


