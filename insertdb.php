<?php
include "lib.php";
$redisconnect=connectredis();
$sql="insert into post ('postid','contents','times','userid','username') values";
$i=0;
while ($redisconnect->lLen('global:newsdatas') && $i++<1000){
    $postid=$redisconnect->rPop('global:newsdatas');
    $postinfo=$redisconnect->hMget('post:postid:'.$postid,array('contents','times','userid','username'));
    $sql.="(".$postid.",'".$postinfo['contents']."','".$postinfo['times']."',".$postinfo['userid'].",'".$postinfo['username']."'),";
}
if($i==0){
    echo 'no job';exit();
}

$sql=substr($sql, 0,-1);
print_r($sql);
$conn=connectmysql();
mysql_query($sql,$conn);

echo 'ok';