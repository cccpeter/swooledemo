<?php
$redisClient = new swoole_redis;// Swoole\Redis
$redisClient->connect('127.0.0.1', 6379, function(swoole_redis $redisClient, $result) {
    echo "connect".PHP_EOL;
    var_dump($result);

    // 同步 
    // redis (new Redis())->set('key',2);
    // $redisClient->set('singwa_1', time(), function(swoole_redis $redisClient, $result) {
    //     var_dump($result);
    // });

    $redisClient->get('singwa_1', function(swoole_redis $redisClient, $result) {
        var_dump($result);
        $redisClient->close();
    });
    //官方文档的demo
	$client = new swoole_redis;
	$client->connect('127.0.0.1', 6379, function (swoole_redis $client, $result) {
	    if ($result === false) {
	        echo "connect to redis server failed.\n";
	        return;
	    }
	    $client->set('key', 'swoole', function (swoole_redis $client, $result) {
	        var_dump($result);
	    });
	});
    
    $redisClient->keys('*gw*', function(swoole_redis $redisClient, $result) {
        var_dump($result);
        $redisClient->close();
    });

});
echo "start".PHP_EOL;