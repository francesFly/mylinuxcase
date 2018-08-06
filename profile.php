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
//分页
$allpage=ceil($redisconnect->lLen('mynewdatas:'.$currentid)/20);
$pagenum=G('pagenum')?G('pagenum'):1;
$pagestart=($pagenum-1)*20;
$pageend=$pagenum*20-1;
$newnumlist=$redisconnect->lrange('mynewdatas:'.$currentid,$pagestart,$pageend);
//sort($newnumlist,SORT_NUMERIC);
//$newnumlist=$redisconnect->sort('mynewdatas:'.$logininfo['userid'],array('sort'=>'desc'));
?>
<h2 class="username"><?php echo $currentname;?></h2>
<a href="follow.php?uid=<?php echo $currentid;?>&f=<?php echo $isfriending; ?>" class="button"><?php echo $friendingstatus; ?></a>

<?php /* foreach ($newlist as $key=>$values){ */
foreach ($newnumlist as $key=>$values){
    $newinfo=$redisconnect->hMget('post:postid:'.$values,array('username','contents','times'));
?>

<div class="post">
<a class="username" href="profile.php?u=<?php echo $newinfo['username'];?>"><?php echo $newinfo['username'];?></a> <?php echo $newinfo['contents'];?><br>
<i><?php echo calculatetime($newinfo['times']);?>前 通过 web发布</i>
</div>
<?php }?>
<?php include 'food.php';?>