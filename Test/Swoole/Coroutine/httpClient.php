<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/14
 * Time: 上午8:43
 */

go(function(){
    $cli = new \Swoole\Coroutine\Http\Client('http://www.biquge.com.tw', 80);
    $cli->setHeaders([
        'Host'            => "http://www.biquge.com.tw",
        "User-Agent"      => 'Chrome/49.0.2587.3',
        'Accept'          => 'text/html,application/xhtml+xml,application/xml',
        'Accept-Encoding' => 'gzip',
    ]);
    $cli->set(['timeout'=>4,]);
    $cli->setDefer();
    $cli->get('/14_14055/9198191.html');
    $cli->recv();
    $res = $cli->statusCode;
    var_dump($res);
});
echo date('Y-m-d H:i:s');