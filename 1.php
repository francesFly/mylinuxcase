<?php


      $redis= newRedis();

      $redis->connect('127.0.0.1',6379);


      $redis->set('name','xxx');


      echo$redis->get('name');

?>