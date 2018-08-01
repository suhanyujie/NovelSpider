<?php
/**
 * start_list.php文件的作用：
 * 1.启动一个进程L，获取一个小说的列表，将列表作为任务，放到redis队列中
 * 2.当"获取详情"的进程C，与L进行通信，获取一个任务(一个详情的url)，L就把队列中的一个任务发放给C。
 * 3.C获取到了任务后，就执行自己的逻辑。
 *
 */
require __DIR__."/../../vendor/autoload.php";

use \Workerman\Worker;
use \Workerman\Lib\Timer;

use QL\QueryList;
use Novel\NovelSpider\Controller\Test;
use Predis\Client;
use Novel\NovelSpider\Models\ListModel;
use Novel\NovelSpider\Models\ContentModel;
use Illuminate\Contracts\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

//数据库加载配置文件
$database = [
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'bbs_test',
    'username'  => 'root',
    'password'  => '123456',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];
$capsule = new Capsule;
$capsule->addConnection($database);
$capsule->setAsGlobal();
$capsule->bootEloquent();

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
    try{
        if(!$res){
            echo "Mysql中也没有尚未抓取的url啦~1".PHP_EOL;
        }else{
            $pushResult = $novel->pushIntoRedis($res);
        }
    }catch (\Exception $e){

    }
    if ($pushResult['status'] != 1) {
        echo $pushResult['message'].PHP_EOL;
        return;
    }
    $length = $redis->llen($listKey);
    echo 'process for list start~'.$length.PHP_EOL;
    // 定时获取最新列表
    $time_interval = 3600*0.5;
    Timer::add($time_interval, function(){
        echo "task run\n";
        // 如果该列表已经爬取过,那么只需爬取这个列表页的最后几条最新数据,放入redis队列中
        $hasSpider = true;
    });
};
$listTask->onMessage = function($connection, $data) use ($listKey,$count)
{
    $count++;
    $data = json_decode($data,true);
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