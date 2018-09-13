<?php
$http = new swoole_http_server('0.0.0.0', 9001);

$http->on('request', function($request, $response) {
    // 获取redis 里面 的key的内容， 然后输出浏览器

    $redis = new Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $value = $redis->get($request->get['a']);

    // mysql.....

    //执行时间取它们中最大的：time = max(redis,mysql)协程的特性。mysql下面的逻辑的执行时间如果比redis的执行时间短那么系统的执行时间以redis的执行时间为准
    $response->header("Content-Type", "text/plain");
    $response->end($value);
    
});

$http->start();    