<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/22
 * Time: 上午9:34
 */

require __DIR__ . "/../../vendor/autoload.php";

use \Workerman\Worker;

$http_worker = new Worker('http://0.0.0.0:3001');
$http_worker->count = 2;
$http_worker->name = 'testHttpServer';
$http_worker->onMessage = function ($connection, $data) {
    var_dump($data);
    $connection->send('123123123123');
};
Worker::runAll();

