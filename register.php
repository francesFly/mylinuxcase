<?php
include 'lib.php';
$username=P("username");
$password=P("password");
$password2=P("password2");

if(!$username || !$password || !$password2){
    errer("请输入完整的注册信息！");
}

if($password!=$password2){
    errer("两次密码不一致！");
}

$redisconnect=connectredis();
if($redisconnect->get("user:username:".$username.":userid")){
    errer("您输入的用户名已存在，请重新输入其他用户名！");
}

$userid=$redisconnect->incr("global.userid");
//$redisconnect->set("user:userid:".$userid.":userid",$userid);
$redisconnect->set("user:userid:".$userid.":username",$username);
$redisconnect->set("user:userid:".$userid.":password",md5($password));
$redisconnect->set("user:username:".$username.":userid",$userid);
success("注册成功！");