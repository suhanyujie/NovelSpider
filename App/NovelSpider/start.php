<?php
require __DIR__."/../../vendor/autoload.php";

use \Workerman\Worker;
use \Workerman\Lib\Timer;

use QL\QueryList;
use Novel\NovelSpider\Controller\Test;
use Predis\Client;
use Novel\NovelSpider\Models\ListModel;
use Novel\NovelSpider\Models\ContentModel;


$redis = new Predis\Client();
$redis->del('url-list');
echo '已清空url-list中的数据了~'.PHP_EOL;


$task = new Worker();
// 开启多少个进程运行定时任务，注意多进程并发问题
$task->count = 5;
$task->onWorkerStart = function($task) {
    $keyConfig = [
        'list-key'=>'novel-list-key',
        'detail-key'=>'novel-detail-key',
    ];
    // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
    if($task->id === 0){
        echo "worker 0 start for list~".PHP_EOL;
        $lisrModel = new ListModel();
        // 获取列表
//        $novel = new Test();
//        $list = $novel->getList();
//        foreach($list as $k=>$v){
//            $data = [
//                'novel_id'=>2,// 2 大宋王侯
//                'url'=>$v['link'],
//                'flag'=>0,
//            ];
//            $lisrModel->insertData($data);
//        }
        // 将列表存进redis存放
        // 从redis取出数据
        //$redis = new Predis\Client();
        //$res = $redis->hgetall($keyConfig['list-key']);
        //$res = $novel->getListFromRedis($keyConfig['list-key']);
        // 读取redis队列,去爬取详情
        // 定时请求,保证获取最新
        $time_interval = 3;
        Timer::add($time_interval, function(){
            echo "task run\n";
            // 获取最新的最后一个url,查看是否与mysql中的最新的url,是否一致,不一致,则把最新的url等数据加入mysql
        });
    } else if($task->id >= 1){ // 进程1号 抓取详情
        echo "worker ".$task->id." start for detail~".PHP_EOL;

        // 抓取详情
        $novel = new Test();
        $res = $novel->getDetail(2);
        $i = 0;
        do{
            if(!$res)continue;
            $errFlag = $res ? 0 : 1;
            $data = [
                'list_id'=>2,
                'title'=>$res['title'],
                'content'=>$res['content'],
                'worker_id'=>$task->id,
                'date'=>date('Y-m-d H:i:s'),
                'err_flag'=>$errFlag,
            ];
            $conModel = new ContentModel();
            $conModel->insertData($data);
            echo "任务".$task->id.'->'.$i."完成".PHP_EOL;
            $res = $novel->getDetail(2);
            $i++;
        }while($res);

        echo "detail finished~".PHP_EOL;

    }// end of if


};

// 运行worker
Worker::runAll();