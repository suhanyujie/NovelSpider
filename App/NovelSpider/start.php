<?php
require __DIR__."/../../vendor/autoload.php";

use \Workerman\Worker;
use \Workerman\Lib\Timer;

use QL\QueryList;
use Novel\NovelSpider\Controller\Test;
use Predis\Client;
use Novel\NovelSpider\Models\ListModel;

$task = new Worker();
// 开启多少个进程运行定时任务，注意多进程并发问题
$task->count = 1;
$task->onWorkerStart = function($task) {
    $keyConfig = [
        'list-key'=>'novel-list-key',
        'detail-key'=>'novel-detail-key',
    ];
    // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
    if($task->id === 0){
        $lisrModel = new ListModel();
        // 获取列表
        $novel = new Test();
        $list = $novel->getList();
        foreach($list as $k=>$v){
            $data = [
                'novel_id'=>2,// 2 大宋王侯
                'url'=>$v['link'],
                'flag'=>0,
            ];
            $lisrModel->insertData($data);
        }
        //$novel->getListFromMysql(2);

        //$redis = new Predis\Client();
        // 将列表存进redis存放
        // 从redis取出数据
        //$res = $redis->hgetall($keyConfig['list-key']);
        //$res = $novel->getListFromRedis($keyConfig['list-key']);

        //var_dump($list);

        // 读取redis队列,去爬取详情
        // 定时请求,保证获取最新
        $time_interval = 3;
        Timer::add($time_interval, function(){
            echo "task run\n";
        });
    } else if($task->id === 1){ // 进程2号 抓取详情
        // 抓取详情
        $novel = new Test();
        $res = $novel->getListFromMysql(2);
        $novel->pushIntoRedis($res);



    }// end of if


};

// 运行worker
Worker::runAll();