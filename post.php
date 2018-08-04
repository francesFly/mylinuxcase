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
$addtime=time();
//字符类型存储
/* $redisconnect->set("post:postid:".$postid.":contents",$contents);
$redisconnect->set("post:postid:".$postid.":times",$addtime);
$redisconnect->set("post:postid:".$postid.":userid",$logininfo['userid']); */
//hush存储
$redisconnect->hMset('post:postid:'.$postid,array('contents'=>$contents,'times'=>$addtime,'userid'=>$logininfo['userid'],'username'=>$logininfo['username']));


//微博推送方式
/* $redisconnect->lPush('releasenewlink:'.$logininfo['userid'],$postid);
$fanslist=$redisconnect->sMembers('fans:'.$logininfo['userid']);
foreach ($fanslist as $key=>$values){
    $redisconnect->lPush('releasenewlink:'.$values,$postid);
}
 */
//微博拉取方式   粉丝查看
$redisconnect->zAdd('releasedatas:userid'.$logininfo['userid'],$addtime,$postid);
if($redisconnect->zCard('releasedatas:userid'.$logininfo['userid'])>20){
    $redisconnect->zRemRangeByRank('releasedatas:userid'.$logininfo['userid'],0,0);
}
//个人查看 数据 1000
$redisconnect->lPush('mynewdatas:'.$logininfo['userid'],$postid);
if($redisconnect->llen('mynewdatas:'.$logininfo['userid'])>1000){
    $redisconnect->rpoplpush('mynewdatas:'.$logininfo['userid'],'global:newsdatas');
}

header("location:home.php");exit();

