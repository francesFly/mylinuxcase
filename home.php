<?php 
include 'head.php';
include "lib.php";
if(($logininfo=islogin())==false){
    header("location:index.php");exit();
}
$redisconnect=connectredis();
$friendingnum=$redisconnect->scard('friending:'.$logininfo['userid']);
$fansnum=$redisconnect->scard('fans:'.$logininfo['userid']);
//微博推送方式
/* $newnum=$redisconnect->ltrim('releasenewlink:'.$logininfo['userid'],0,50); */
//字符串存储
/* $newlist=$redisconnect->sort('releasenewlink:'.$logininfo['userid'],array('sort'=>'desc','get'=>'post:postid:*:contents')); */
//hush存储
/* $newnumlist=$redisconnect->sort('releasenewlink:'.$logininfo['userid'],array('sort'=>'desc')); */
//拉取存储
$friendinglist=$redisconnect->sMembers('friending:'.$logininfo['userid']);
$friendinglist[]=$logininfo['userid'];
$pulltime=time();
$pulllasttime=$redisconnect->get('pulltime:userid:'.$logininfo['userid']);//最后一次拉取时间
if(!$pulllasttime){
    $pulllasttime=0;
}
$redisconnect->set('pulltime:userid:'.$logininfo['userid'],$pulltime);
$pullnewlistLast=array();
foreach ($friendinglist as $key=>$values){
    $pullnewlistLast=array_merge($pullnewlistLast,$redisconnect->zRangeByScore('releasedatas:userid:'.$values,$pulllasttime+1,$pulltime));
}
if($pullnewlistLast){
sort($pullnewlistLast,SORT_NUMERIC);
}
//print_r(count($pullnewlistLast));
foreach ($pullnewlistLast as $key=>$values){
    $redisconnect->lPush('releasepast:'.$logininfo['userid'],$values);
}
$redisconnect->ltrim('releasepast:'.$logininfo['userid'],0,999);
//分页
$allpage=ceil($redisconnect->lLen('releasepast:'.$logininfo['userid'])/20);
$pagenum=G('pagenum')?G('pagenum'):1;
$pagestart=($pagenum-1)*20;
$pageend=$pagenum*20-1;
$newnumlist=$redisconnect->lrange('releasepast:'.$logininfo['userid'],$pagestart,$pageend);
//$newnumlist=$redisconnect->sort('releasepast:'.$logininfo['userid'],array('sort'=>'desc'));
?>
<div id="postform">
<form method="POST" action="post.php">
<?php echo $logininfo['username'];?>, 有啥感想?
<br>
<table>
<tr><td><textarea cols="70" rows="3" name="status"></textarea></td></tr>
<tr><td align="right"><input type="submit" name="doit" value="Update"></td></tr>
</table>
</form>
<div id="homeinfobox">
<?php echo $fansnum;?> 粉丝<br>
<?php echo $friendingnum;?> 关注<br>
</div>
</div>
<?php /* foreach ($newlist as $key=>$values){ */
foreach ($newnumlist as $key=>$values){
    $newinfo=$redisconnect->hMget('post:postid:'.$values,array('username','contents','times'));
?>
<?php 
for($i=1;$i<$allpage;$i++){
?>
<a class="pagebtn" href="home.php?pagenum=<?php echo $i;?>"><?php echo $i;?></a>
<?php }?>
<div class="post">
<a class="username" href="profile.php?u=<?php echo $newinfo['username'];?>"><?php echo $newinfo['username'];?></a> <?php echo $newinfo['contents'];?><br>
<i><?php echo calculatetime($newinfo['times']);?>前 通过 web发布</i>
</div>
<?php }?>
<?php include 'food.php';?>
