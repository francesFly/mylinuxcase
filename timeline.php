<?php
include 'head.php';
include "lib.php";
if(($logininfo=islogin())==false){
    header("location:index.php");exit();
}
$redisconnect=connectredis();
$newuserlist=array();
$newuserlist=$redisconnect->sort('newuserlink',array('sort'=>'desc','get'=>'user:userid:*:username'));

?>
<h2>热点</h2>
<i>最新注册用户(redis中的sort用法)</i><br>
<div>
<?php foreach($newuserlist as $key =>$values){?>
<a class="username" href="profile.php?u=<?php echo $values?>"><?php echo $values?></a> 
<?php }?>
</div>

<br><i>最新的50条微博!</i><br>
<?php 
for ($i=0;$i<50;$i++){
    $postid=$redisconnect->get('global:postid')-$i;
    $newinfo=$redisconnect->hMget('post:postid:'.$postid,array('username','contents','times'));
?>

<div class="post">
<a class="username" href="profile.php?u=<?php echo $newinfo['username'];?>"><?php echo $newinfo['username'];?></a> <?php echo $newinfo['contents'];?><br>
<i><?php echo calculatetime($newinfo['times']);?>前 通过 web发布</i>
</div>
<?php }?>


<?php include 'food.php';?>