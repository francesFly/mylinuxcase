<?php
$userid=$_COOKIE["userid"];
setcookie("username",'',-1);
setcookie("userid",'',-1);
setcookie("loginvalidation",'',-1);
include "lib.php";
$redisconnect=connectredis();
$redisconnect->set('user:userid:'.$userid.':loginvalidation','');
$redisconnect->expire('user:userid:'.$userid.':loginvalidation',1);
header("location:index.php");exit();