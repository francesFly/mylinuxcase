<?php
// get instance
$redis = new Redis();
// connect to redis server
$redis->open('localhost',6379);
$redis->set('user:userid:9:username','wangwu');
var_dump($redis->get('user:userid:9:username'));
?>