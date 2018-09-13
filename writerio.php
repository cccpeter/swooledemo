<?php
$http->on('request', function($request, $response) {
    $content = [
        'date:' => date("Ymd H:i:s"),
        'get:' => $request->get,
        'post:' => $request->post,
        'header:' => $request->header,
    ];
    $filename=__DIR__."/1.txt";
    swoole_async_writefile($filename, json_encode($content).PHP_EOL, function($filename){
        // todo
    }, FILE_APPEND);
    $response->end("response：". json_encode($request->get));
});