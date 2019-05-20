<?php
require __DIR__."/../../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use Novel\NovelSpider\Controller\Test;
use Novel\NovelSpider\Services\DataCacheService;
use Workerman\Lib\Timer;
use Workerman\Worker;
use Novel\NovelSpider\Models\NovelContentModel;
use Novel\NovelSpider\Services\NovelContentService;

// 解析配置文件
//定义全局常量
define('ROOT', realpath(__DIR__.'/../../'));
//解析配置文件
$envConfig = parse_ini_file(ROOT . "/.env", true);
DataCacheService::set('envConfigArr', $envConfig);
$dbConfig = $envConfig['start_list_db'] ?? [];

//数据库加载配置文件
$database = [
    'driver'    => 'mysql',
    'host'      => $dbConfig['DB_HOST'],
    'database'  => $dbConfig['DB_DATABASE'],
    'username'  => $dbConfig['DB_USER'],
    'password'  => $dbConfig['DB_PASSWORD'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
];
$capsule = new Capsule;
$capsule->addConnection($database);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$task = new Worker();
// 开启多少个进程运行定时任务，注意多进程并发问题
$task->count = 4;
$task->onWorkerStart = function($task) {
    $keyConfig = [
        'list-key'   => 'novel-list-key',
        'detail-key' => 'novel-detail-key',
    ];
    $listKey = 'novel-list-key';
    $redis = new Predis\Client();
    $contentService = new NovelContentService();
    // 只在id编号为0的进程上设置定时器，其它1、2、3号进程不设置定时器
    if($task->id >=0){
        echo "worker ".$task->id." start for detail~".PHP_EOL;
        while(1)
        {
            // the data is likely : {"id":2462,"novel_id":6,"name":"\\u7b2c2118\\u7ae0\\u91cd\\u78c5","chapter_num":2,"url":"https:\\/\\/www.biquge5.com\\/1_1216\\/19896689.html","desc":"","flag":2,"err_flag":0,"add_time":"2019-04-27 18:04:40","update_time":"2019-04-27 18:04:40"}
            $oneData = $redis->rpop($listKey);
            $taskLength = $redis->llen($listKey);
            echo "Info:\t队列中剩余任务数量：{$taskLength}\n";
            if (empty($oneData)) {
                echo "Info:\t当前队列中没有任务数据...\n";
                sleep(10);
            }
            // $oneData = '{"id":2462,"novel_id":6,"name":"\\u7b2c2118\\u7ae0\\u91cd\\u78c5","chapter_num":2,"url":"https:\\/\\/www.biquge5.com\\/1_1216\\/19896689.html","desc":"","flag":2,"err_flag":0,"add_time":"2019-04-27 18:04:40","update_time":"2019-04-27 18:04:40"}';
            //取出单个数据后，获取具体的详细信息
            $oneData = json_decode($oneData, true);
            try {
                $contentService->getDetail($oneData, $task->id);
            }catch (\Exception $e) {
                var_dump($e->getMessage());
            }
            $random = mt_rand(1,3);
            sleep($random);
        }

        /*$count = 0;
        $runFlag = true;
        // 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
        $taskConnetion = new AsyncTcpConnection('Text://127.0.0.1:3001');
        // 异步获得结果
        $taskConnetion->onMessage = function($taskConnetion, $taskResult) use ($novel,$conModel,$task,&$count,$runFlag)
        {
            // url已经发放完毕了,没有需要抓取的url了
            if(!$taskResult){
                $runFlag = false;
                return;
            }
            // 获取详情页的结果
            $res = $novel->getDetail($taskResult);
            //var_dump( $res );
            if(!$res){
                $res=0;
                throw new Exception("article detail error");
            }
            if(!isset($res['title'])){
                $res=0;
                throw new Exception("title is empty! error");
            }
            $errFlag = $res ? 0 : 1;
            $data = [
                'list_id'=>2,
                'chapter'=>$res['chapter'],
                'title'=>$res['title'],
                'content'=>$res['content'],
                'worker_id'=>$task->id,
                'date'=>date('Y-m-d H:i:s'),
                'err_flag'=>$errFlag,
            ];
            $conModel->insertData($data);
            echo "任务".$task->id.'->'.$count."完成~~~~~~~~~~~~".$count.PHP_EOL;
            $count++;
            $taskConnetion->curTaskResStatus = $taskResult ? 1 : 0;
        };
        $novel->requestTaskDataFromProcess($taskConnetion,[]);
        $i = 1;
        while(true){
            //echo $i.PHP_EOL;
            if($i > 5 || !$runFlag) break;// 防止无法退出while循环的情况
            $novel->requestTaskDataFromProcess($taskConnetion,[]);
            $i++;
        }
        // 获得结果后记得关闭异步链接
        $taskConnetion->close();*/
        
        // 自动更新最新连载
//        $dealUpdateService = new SdealUpdate( 1 );
//        $dealUpdateService->getInternetUpdate();

        // 定时请求,保证获取最新
        $time_interval = 2;// 3600*1.5  秒数
        $timerId = Timer::add($time_interval, function(){
            echo "task run\n";
            $novel = new Test();
            // 去列表库中最新的一个url,和此次抓取的进行对比
            // 如果不是最新的,那么就抓取这一章后面的更新的章节
            // $novel->getLatestChapter();
            // 获取最新的最后一个url,查看是否与mysql中的最新的url,是否一致,不一致,则把最新的url等数据加入mysql
            // 获取所有小说
//            $dbObj = Db::instance('db1');
//            $allNovel = $dbObj->select('*')->from('novel_main')->where('novel_status=0')->orderByDESC(['id'])->limit(2)->query();
//            if ($allNovel) {
//                foreach ($allNovel as $k => $row) {
//                    $dealUpdateService = new SdealUpdate($row['id']);
//                    $dealUpdateService->getListUrl();
//                }
//            }
        });
    }// end of onstart function
};

// 运行worker
Worker::runAll();
