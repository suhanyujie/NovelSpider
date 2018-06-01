<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/23
 * Time: 下午10:59
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Workerman\Worker;

$worker = new Worker('http://0.0.0.0:3002');
$worker->count = 1;

$worker->onMessage = function ($conn, $data) {
    $post = $data['post'];
    var_dump($post);
    $result = $post;
    $conn->send(json_encode($result, JSON_UNESCAPED_UNICODE));


//    $taskConn = new \Workerman\Connection\AsyncTcpConnection('text://127.0.0.1:3003');
//    $taskConn->send('deal data');
//
//    $taskConn->onMessage = function ($taskConn, $taskResult) use (&$conn) {
//        $taskConn->close();
//        $conn->send($taskResult);
//        $conn->close();
//    };
//    $taskConn->connect();
};

$taskWorker = new Worker('Text://0.0.0.0:3003');
$taskWorker->name = 'task worker';
$taskWorker->onMessage = function ($conn, $taskData) {
    var_dump($taskData);
    $data = [
        'status'  => 1,
        'message' => 'success',
    ];
    $conn->send(json_encode($data, JSON_UNESCAPED_UNICODE));
};

Worker::runAll();


