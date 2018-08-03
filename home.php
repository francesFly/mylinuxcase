<?php 
include 'head.php';
include "lib.php";
if(($logininfo=islogin())==false){
    header("location:index.php");exit();
}
$redisconnect=connectredis();
$friendingnum=$redisconnect->scard('friending:'.$logininfo['userid']);
$fansnum=$redisconnect->scard('fans:'.$logininfo['userid']);
$newnum=$redisconnect->ltrim('releasenewlink:'.$logininfo['userid'],0,50);
$newlist=$redisconnect->sort('releasenewlink:'.$logininfo['userid'],array('sore'=>'desc','get'=>'post:postid:*:contents'));
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
<?php foreach ($newlist as $key=>$values){?>
<div class="post">
<a class="username" href="profile.php?u=test">test</a> <?php echo $values;?><br>
<i>11 分钟前 通过 web发布</i>
</div>
<?php }?>
<?php include 'food.php';?>
