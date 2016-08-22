<?php
require __DIR__."/../../vendor/autoload.php";

use \Workerman\Worker;
use \Workerman\Lib\Timer;

use QL\QueryList;
use Novel\NovelSpider\Controller\Test;
use Predis\Client;
use Novel\NovelSpider\Models\ListModel;
use Novel\NovelSpider\Models\ContentModel;


// 开启worker专门分发任务url数据
$listTask = new Worker('text://0.0.0.0:3001');
$listTask->count = 1;
$listTask->user = 'list-process';
$listKey = 'novel-list-key';
$count = 0;
$listTask->onWorkerStart = function($listTask)
{
    $listKey = 'novel-list-key';
    $redis = new Predis\Client();
    $redis->del($listKey);
    $novel = new Test();
    $res = $novel->getListFromMysql(2);
    if(!$res){
        echo "Mysql中也没有尚未抓取的url啦~1".PHP_EOL;
    }else{
        $novel->pushIntoRedis($res);
    }
    $length = $redis->llen($listKey);
    echo 'process for list start~'.$length.PHP_EOL;
};
$listTask->onMessage = function($connection, $data) use ($listKey,$count)
{
    $count++;
    $data = json_decode($data,true);
    var_dump($data);
    if($data['args']['contents'] === 'refresh-list'){
        $redis = new Predis\Client();
        $redis->del($listKey);
        return;
    }
    $novel = new Test();
    $sendData = $novel->getNextTaskData();
    $connection->send(json_encode($sendData));
    echo 'request recieved '.$count.PHP_EOL;
};

// 运行worker
Worker::runAll();