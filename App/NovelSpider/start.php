<?php
require __DIR__."/../../vendor/autoload.php";

use \Workerman\Worker;
use \Workerman\Lib\Timer;

use QL\QueryList;
use Novel\NovelSpider\Controller\Test;

$task = new Worker();
// 开启多少个进程运行定时任务，注意多进程并发问题
$task->count = 1;
$task->onWorkerStart = function($task) {
    // 获取列表
    $novel = new Test();
    $novel->getList();
    // 获取详情

    // 定时请求,保证获取最新
    $time_interval = 3;
    Timer::add($time_interval, function(){
        echo "task run\n";
    });

};

// 运行worker
Worker::runAll();