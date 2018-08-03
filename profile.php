<?php
include 'head.php';
include "lib.php";
if(($logininfo=islogin())==false){
    header("location:index.php");exit();
}
$currentname=G('u');
if(!$currentname){
    errer("非法操作！");
}
$redisconnect=connectredis();
$currentid=$redisconnect->get('user:username:'.$currentname.':userid');
if(!$currentid){
    errer("非法用户！");
}
$isfriending=$redisconnect->sismember('friending:'.$logininfo['userid'],$currentid);
$friendingstatus=$isfriending?'取消关注':'立即关注';
?>
<h2 class="username"><?php echo $fansname;?></h2>
<a href="follow.php?uid=<?php echo $currentid;?>&f=<?php echo $isfriending; ?>" class="button"><?php echo $friendingstatus; ?></a>

<div class="post">
<a class="username" href="profile.php?u=test">test</a> 
world<br>
<i>11 分钟前 通过 web发布</i>
</div>

<div class="post">
<a class="username" href="profile.php?u=test">test</a>
hello<br>
<i>22 分钟前 通过 web发布</i>
</div>
<?php include 'food.php';?>