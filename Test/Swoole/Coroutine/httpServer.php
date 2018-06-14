<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/14
 * Time: 上午8:54
 */
$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("request", function ($request, $response) {
    $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
    // swoole schedule trigger coroutine before connect
    $client->connect("127.0.0.1", 8888, 0.5);
    $client->send("hello world from swoole");
    // swoole schedule trigger coroutine on recv
    $ret = $client->recv();
    $response->header("Content-Type", "text/plain");
    $response->end('no response...');
    $client->close();
});

$http->start();
