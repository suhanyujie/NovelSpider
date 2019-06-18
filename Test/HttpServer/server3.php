<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/6/18
 * Time: 15:18
 */


require_once __DIR__ . "/../../vendor/autoload.php";

use Workerman\Worker;

$apiPort = 3001;
$apiServ = new Worker('tcp://0.0.0.0:'.$apiPort);

$apiServ->onMessage = function ($connection, $data) {
    var_dump($data);
};

Worker::runAll();
