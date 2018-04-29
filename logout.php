<?php
//先启动Session
session_start();
//清除Session中的用户信息
unset($_SESSION['user']);
//退出成功，自动跳转到登录页
header('Location: login_l.php');
exit;
