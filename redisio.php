<?php
// $redis=new Swoole\Coroutine\Redis();
// $redis->connect('127.0.0.1',6379);
// $redis->get('key');
$http=new swoole_http_server('0.0.0.0',8001);
$http->on('request',function($request,$response){
    //获取redis的内容逻辑
    $redis=new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1',6379);
    $value=$redis->get($request->get['key']);
    $response->header("Content-Type","text/plain");
    $response->end($value);
});
$http->start();