<?php
function P($key){
    return $_POST[$key];
}
function errer($msg){
    include "head.php";
    echo $msg;
    include "food.php";
    exit();
}
function success($msg){
    include "head.php";
    echo $msg;
    include "food.php";
    exit();
}
/*
  $redis = new Redis();
  $redis->connect('127.0.0.1',6379,1);//�����ӣ�����host���˿�Ϊ6379������1���������
  $redis->open('127.0.0.1',6379,1);//������(ͬ��)
  $redis->pconnect('127.0.0.1',6379,1);//�����ӣ�����host���˿�Ϊ6379������1���������
  $redis->popen('127.0.0.1',6379,1);//������(ͬ��)
  $redis->auth('password');//��¼��֤���룬���ء�true | false��
  $redis->close();//�ͷ���Դ
  $redis->ping(); //����Ƿ�������,[+pong]
 */
function connectredis(){
    static $redisconnect=NULL;
    if($redisconnect!==NULL){
        return $redisconnect;
    }
    $redisconnect = new Redis();
    $redisconnect->connect('localhost',6379,1);
    return $redisconnect;
}