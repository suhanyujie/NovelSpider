<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/3/9
 * Time: 11:37
 */


require_once __DIR__ . "/../../../vendor/autoload.php";

use Workerman\Worker;

$apiPort = 8002;
$apiServ = new Worker('tcp://0.0.0.0:'.$apiPort);

$apiServ->onWorkerStart = function () use ($apiPort) {
    echo "server has start in port :{$apiPort}\n";
};

$apiServ->onMessage = function ($connection, $data) {
    var_dump($data);
};

Worker::runAll();
