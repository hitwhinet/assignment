<?php
//设定字符集
header('content-type:text/html;charset=utf-8');
//载入配置文件
$config = require './data/config.php';
//载入函数库
require './library/function.php';
//启动Session
session_start();
