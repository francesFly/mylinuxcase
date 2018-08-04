<?php
error_reporting(E_ALL & ~E_NOTICE);
function P($key){
    return $_POST[$key];
}
function G($key){
    return $_GET[$key];
}
function errer($msg){
    include "head.php";
    echo $msg;
    include "food.php";
    exit();
}
function success($msg){
    include "head.php";
    echo $msg;
    include "food.php";
    exit();
}
/*
 $redis = new Redis();
 $redis->connect("127.0.0.1",6379,1);//短链接，本地host，端口为6379，超过1秒放弃链接
 $redis->open("127.0.0.1",6379,1);//短链接(同上)
 $redis->pconnect("127.0.0.1",6379,1);//长链接，本地host，端口为6379，超过1秒放弃链接
 $redis->popen("127.0.0.1",6379,1);//长链接(同上)
 $redis->auth("password");//登录验证密码，返回【true | false】
 $redis->close();//释放资源
 $redis->ping(); //检查是否还再链接,[+pong]
 */
function connectredis(){
    static $redisconnect=NULL;
    if($redisconnect!==NULL){
        return $redisconnect;
    }
    $redisconnect = new Redis();
    $redisconnect->connect("localhost",6379,1);
    return $redisconnect;
}

function islogin(){
    $username=$_COOKIE["username"];
    $userid=$_COOKIE["userid"];
    $loginvalidation=$_COOKIE["userid"];
    if(!$username || !$userid || !$loginvalidation){
        return false;
    }
    $connectredis=connectredis();
    $invalidation=$connectredis->get('user:userid:'.$userid.':loginvalidation');
    if($invalidation!=$loginvalidation){
        return false;
    }      
    $connectredis->set('user:userid:'.$userid.':loginvalidation',$loginvalidation,'ex',300);
    setcookie("loginvalidation",$loginvalidation,time()+300);
    setcookie("username",$username,time()+300);
    setcookie("userid",$userid,time()+300);
    return array('username'=>$username,'userid'=>$userid);
}

function randomstr(){
    $str='qazwsxedcvfrtgbnhyujmkiolp1234567890-=%$#@!*&^()?ZAQWSXCDERFVBGTYHNMJUIKLOP';
    $lstr=strrev(str_shuffle($str));
    $randstr=substr($lstr, 2,18);
    return $randstr;
}