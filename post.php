<?php
include "lib.php";
if(($logininfo=islogin())==false){
    errer("请先退出登录，在进行登录！");
}
$contents=P("status");
if(!$contents){
    errer("请输入您的感想！");
}
$redisconnect=connectredis();
$postid=$redisconnect->incr('global:postid');
$redisconnect->set("post:postid:".$postid.":contents",$contents);
$redisconnect->set("post:postid:".$postid.":time",time());
$redisconnect->set("post:postid:".$postid.":userid",$logininfo['userid']);

//微博推送
$redisconnect->lPush('releasenewlink:'.$logininfo['userid'],$postid);
$fanslist=$redisconnect->sMembers('fans:'.$logininfo['userid']);
foreach ($fanslist as $key=>$values){
    $redisconnect->lPush('releasenewlink:'.$values,$postid);
}

header("location:home.php");exit();

