<?php
include "lib.php";
if(($logininfo=islogin())==false){
    header("location:index.php");exit();
}
$friendingid=G('uid');
$friendingstatus=G('f');
if(!$friendingid){
    errer("非法操作！");
}
if($friendingid==$logininfo['userid']){
    errer("不能对自己进行关注相关操作！");
}
$redisconnect=connectredis();
$friendingname=$redisconnect->get('user:userid:'.$friendingid.':username');
if(!$friendingname){
    errer("非法用户！");
}

if($friendingstatus){//已关注过，现在进行取消
    $redisconnect->srem('friending:'.$logininfo['userid'],$friendingid);
    $redisconnect->srem('fans:'.$friendingid,$logininfo['userid']);
}else{
    $redisconnect->sadd('friending:'.$logininfo['userid'],$friendingid);
    $redisconnect->sadd('fans:'.$friendingid,$logininfo['userid']);
}

header('location:profile.php?u='.$friendingname);

