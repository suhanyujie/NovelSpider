<?php
/**
 * start_list.php文件的作用：
 * 1.启动一个进程L，获取一个小说的列表，将列表作为任务，放到redis队列中
 * 2.当"获取详情"的进程C，与L进行通信，获取一个任务(一个详情的url)，L就把队列中的一个任务发放给C。
 * 3.C获取到了任务后，就执行自己的逻辑。
 *
 */
require __DIR__."/../../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use Novel\NovelSpider\Controller\Test;
use Novel\NovelSpider\Models\NovelMainModel;
use Novel\NovelSpider\Services\NovelCacheKeyConfigService;
use Novel\NovelSpider\Services\NovelService;
use Workerman\Worker;

// 解析配置文件
//定义全局常量
define('ROOT', realpath(__DIR__.'/../../'));
//解析配置文件
$envConfig = parse_ini_file(ROOT . "/.env", true);
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

// 检测redis连接是否通畅
try {
    $redis   = new Predis\Client();
}catch (\Exception $e) {
    echo "redis启动异常：\n";
    echo ($e->getMessage());die;
}

// 开启worker专门分发任务url数据
$listTask = new Worker('Text://0.0.0.0:3001');
$listTask->count = 1;
$listTask->user = 'list-process';
$listKey = NovelCacheKeyConfigService::NOVEL_LIST_KEY;
$count = 0;
$listTask->onWorkerStart = function($listTask)
{
    $currentTime = date('Y-m-d H:i:s');
    //获取列表页的逻辑流程如下
    //根据小说id，去抓取列表页，看看列表页中的最新的章节数是否和已存的一致，如果不一致，则进行更新
    $novelService = new NovelService();
    //获取所有正要抓取列表内容的小说
    $novels = $novelService->getNovelList([
        'novel_status' => [1,3],
        'limit'=>100,
    ]);
    if ($novels->count() < 1) {
        $exampleData = [
            'name'         => "权力巅峰",
            'list_url'     => "https://www.biquge5.com/1_1216/",
            'base_url'     => "https://www.biquge5.com",
            'novel_status' => 3,
            'insert_date'  => $currentTime,
        ];
        $novel = new NovelMainModel();
        $novel->name = $exampleData['name'];
        $novel->list_url = $exampleData['list_url'];
        $novel->base_url = $exampleData['base_url'];
        $novel->novel_status = 3;// 1列表已抓取  3等待抓取列表
        $novel->insert_date = $currentTime;
        if (!$novel->checkExist([
            'list_url'=>$novel->list_url,
        ])) {
            $novel->save();
            $novels = collect([$novel,]);
        }
    }
    //针对每个小说 获取他们的列表页
    $novels->map(function ($item) use ($novelService) {
        // 查看list是否存在，不存在，则进行抓取
        $listExist = $novelService->checkNovelListExist($item->id);
        // 不存在，则抓取列表
        if (!$listExist) {
            $oneOfList = (new NovelMainModel)->find($item->id);
            $novelService->setListUrl($oneOfList->list_url);
            $novelService->setNovelId($item->id);
            $chapterList = $novelService->getList();
            try {
                $novelService->storeList($chapterList);
            }catch (\Exception $e) {
                echo $e->getMessage()."\n";
                return;
            }
        }
        $chapterList = $novelService->getListFromMysql($item->id);
        $listKey = NovelCacheKeyConfigService::NOVEL_LIST_KEY .":". $item->id;
        $redis   = new Predis\Client();
        $redis->del($listKey);
        try {
            if (!$chapterList) {
                echo "Mysql中也没有尚未抓取的url啦~1\n";
            } else {
                $pushResult = $novelService->pushIntoRedis($chapterList);
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return ['status' => $e->getCode(), 'message' => $e->getMessage()];
        }
        if ($pushResult['status'] != 1) {
            echo $pushResult['message'] . PHP_EOL;
        }
        $length = $redis->llen($listKey);
        echo 'process for list start~' . $length . PHP_EOL;
    });
    // 定时获取最新列表
    $time_interval = 3600*0.5;
//    Timer::add($time_interval, function(){
//        echo "task run\n";
//        // 如果该列表已经爬取过,那么只需爬取这个列表页的最后几条最新数据,放入redis队列中
//        $hasSpider = true;
//    });
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
