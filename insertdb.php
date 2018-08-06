<?php
include "/wwwroot/myweb/lib.php";
$redisconnect=connectredis();
$mysqli = new mysqli("127.0.0.1", "root", "root", "weibodb");
if ($mysqli->connect_errno) {    
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
while ($redisconnect->lLen('global:newsdatas')){ 
$sql='';
$i=0;
while ($i++<1000){    
    $postid=$redisconnect->rPop('global:newsdatas');
    //备份现在要导入的数据
    $redisconnect->lPush('global:bakpastid',$postid);
    $postinfo=$redisconnect->hMget('post:postid:'.$postid,array('contents','times','userid','username'));
    $sql.="insert into post (postid,contents,times,userid,username) values (".$postid.",'".$postinfo['contents']."',".$postinfo['times'].",".$postinfo['userid'].",'".$postinfo['username']."');";
}
if($i==0){
    echo 'no job';exit();
}
//$sql=substr($sql, 0,-1);
if($mysqli->multi_query($sql)=== TRUE){
    $len=$redisconnect->llen('global:bakpastid');
    for($k=0;$k<$len;$k++){
       $postid=$redisconnect->lPop('global:bakpastid');
       $redisconnect->del('post:postid:'.$postid);
    }
    $redisconnect->del('global:bakpastid');
    
    echo 'ok';exit();
}else{
    //导入到global:newsdatas中
    foreach ($redisconnect->lrange('global:bakpastid',0,-1) as $key=>$values){
        $postid=$redisconnect->lPop('global:bakpastid');
        $redisconnect->rPush('global:newsdatas',$postid);
    }
    printf("Error: %s\n", $mysqli->error);
    
}
}