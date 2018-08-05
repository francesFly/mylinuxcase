<?php
include "lib.php";
if(islogin()){
    errer("请先退出登录，在进行登录！");
}
$username=P("username");
$password=P("password");
if(!$username || !$password){
    errer("请输入完整的登录信息！");
}
$redisconnect=connectredis();
$userid = $redisconnect->get("user:username:".$username.":userid");
if(!$userid){
    errer("用户名不存在！");
}
$realpassw=$redisconnect->get("user:userid:".$userid.":password");
if($realpassw!=md5($password)){
    errer("密码输入错误！");
}
setcookie("username",$username);
setcookie("userid",$userid);
$loginvalidation=randomstr();
$redisconnect->set('user:userid:'.$userid.':loginvalidation',$loginvalidation);
//$redisconnect->expire('user:userid:'.$userid.':loginvalidation',300);
setcookie("loginvalidation",$loginvalidation);//,time()+300

header("location:home.php");