<?php
include "lib.php";
$redisconnect=connectredis();
$sql="insert into post ('postid','contents','times','userid','username') values";
$i=0;

while ($redisconnect->lLen('global:newsdatas') && $i++<1000){    
    $postid=$redisconnect->rPop('global:newsdatas');
    //备份现在要导入的数据
    $redisconnect->lPush('global:bakpastid',$postid);
    $postinfo=$redisconnect->hMget('post:postid:'.$postid,array('contents','times','userid','username'));
    $sql.="(".$postid.",'".$postinfo['contents']."','".$postinfo['times']."',".$postinfo['userid'].",'".$postinfo['username']."'),";
}
if($i==0){
    echo 'no job';exit();
}

$sql=substr($sql, 0,-1);
print_r($sql);
$conn=connectmysql();
$return=mysql_query($sql,$conn);
if(!$return){
    //导入到global:newsdatas中
    foreach ($redisconnect->lrange('global:bakpastid',0,-1) as $key=>$values){
        $postid=$redisconnect->lPop('global:bakpastid');
        $redisconnect->rPush('global:newsdatas',$postid);
    }
    die('no insert: ' . mysql_error());
}else{
    $redisconnect->del('global:bakpastid');
     echo 'ok';exit();
}