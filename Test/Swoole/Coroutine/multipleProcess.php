<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/14
 * Time: 上午8:59
 */

for ($i = 0; $i < 2; $i++)
{
    $p = new swoole_process(function () use ($i) {
        $port = 9501 + $i;
        $http = new swoole_http_server("127.0.0.1", $port);
        $http->on("start", function ($server) use ($port) {
            echo "Swoole http server is started at http://127.0.0.1:{$port}\n";
        });

        $http->on("request", function ($request, $response) {
            $response->header("Content-Type", "text/plain");
            $response->end("Hello World\n");
        });

        $http->start();
    }, false, false);
    $p->start();
    echo 'service\'s port is: '.$port,PHP_EOL;
}