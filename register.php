<?php
include 'lib.php';
$username=P("username");
$password=P("password");
$password2=P("password2");

if(!$username || !$password || !$password2){
    errer("������������ע����Ϣ��");
}

if($password!=$password2){
    errer("�������벻һ�£�");
}

$redisconnect=connectredis();
if($redisconnect->get("user:username".$username.":userid")){
    errer("��������û����Ѵ��ڣ����������������û�����");
}

$userid=$redisconnect->incr("global.userid");
$redisconnect->set("user:userid:".$userid.":username",$username);
$redisconnect->set("user:userid:".$userid.":password",md5($password));
success("ע��ɹ���");