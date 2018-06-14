<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/14
 * Time: 上午8:43
 */

use Swoole\Coroutine as co;

co::create(function(){
    $cli = new \Swoole\Coroutine\Http\Client('127.0.0.1', 9501);
    $cli->setHeaders([
        'Host'=>'https://www.qu.la',
    ]);
    $result = $cli->get('/book/746');
    var_dump($result);
});