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
$listTask = new Worker('Text://0.0.0.0:3001');
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
    // 定时获取最新列表
    $time_interval = 3600*0.5;
    Timer::add($time_interval, function(){
        echo "task run\n";
        // 获取最新的最后一个url,查看是否与mysql中的最新的url,是否一致,不一致,则把最新的url等数据加入mysql
    });
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
    if(!$sendData){
        $sendData = 0;
    }
    $connection->send(json_encode($sendData));
    echo 'request recieved '.$count.PHP_EOL;
};

// 运行worker
Worker::runAll();