<?php
//载入项目初始化脚本
require './init.php';

//判断用户是否登录，如果登录，获取用户ID
$user_id = checkLogin();  //如果没有登录，自动跳转到登录

//定义储存用户数据的文件路径
$file_path = "./data/$user_id.txt";

//从配置文件获取 血型 的可选值
$blood = $config['userinfo']['blood'];
//从配置文件获取 爱好 的可选值
$hobby = $config['userinfo']['hobby'];

//先判断是否有表单提交
if($_POST){
    //有表单提交时，接收表单数据并输出

	//定义需要接收的字段
	$fields = array('name', 'description', 'gender', 'blood', 'hobby', 'gender');
	//通过循环自动接收数据并进行处理
	$user_data = array();  //用于保存处理结果
	foreach($fields as $v){
	    $user_data[$v] = isset($_POST[$v]) ? $_POST[$v] : '';
	}
	//转义可能存在的HTML特殊字符
	$user_data['name'] = htmlspecialchars($user_data['name']);
	$user_data['description'] = htmlspecialchars($user_data['description']);
	//验证性别是否为合法值
	if($user_data['gender']!='男' && $user_data['gender']!='女'){
		exit('保存失败，未选择性别。');
	}
	//验证血型是否为合法值
	if(!in_array($user_data['blood'], $blood)){
		exit('保存失败，您选择的血型不在允许的范围内。');
	}
	//判断表单提交的“爱好”值是否为数组
	if(is_array($user_data['hobby'])){
		//过滤掉不在预定义范围内的数据
		$user_data['hobby'] = array_intersect($hobby,$user_data['hobby']);
	}elseif(is_string($user_data['hobby'])){
		$user_data['hobby'] = array($user_data['hobby']);
	}
	//验证完成，保存文件
	//将数组序列化为字符串
	$data = serialize($user_data);
	//将字符串保存到文件中
	file_put_contents($file_path,$data);
	//保存成功
	$success = true;
}

//没有表单提交时继续执行原有程序

//定义表单默认数据
$user_data = array(
	'name' => '',
	'gender' => '男',
	'blood' => '未知',
	'hobby' => array(),
	'description' => ''
);

//判断文件是否存在
if(is_file($file_path)){
	//文件存在，从文件中读取用户数据，并与默认数据合并
	$user_data = array_merge($user_data,unserialize(file_get_contents($file_path)));
}

//载入编辑用户信息的页面文件
require "./view/userinfo.html";