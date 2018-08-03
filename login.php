<?php
include 'lib.php';
if(islogin()){
    header('location:home.php');exit();
}
$username=P('username');
$password=P('password');
if(!$username || !$password){
    errer('请输入完整的登录信息！');
}
$redisconnect=connectredis();
$userid = $redisconnect->get('user:username:'.$username.':userid');
if(!$userid){
    errer('用户名不存在！');
}
$realpassw=$redisconnect->get('user:userid:'.$userid.':password');
if($realpassw!=md5($password)){
    errer('密码输入错误！');
}
setcookie('username',$username);
setcookie('userid',$userid);
header('location:home.php');