<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/22
 * Time: 上午9:34
 *
 * 开启后台的web服务 包括接口，前端页面的访问服务 php Novel/NovelAdmin/index.php start
 * 如果是守护进程方式启动，加上参数`-d`
 */

require_once __DIR__ . "/../../vendor/autoload.php";

use Libs\Core\Container\Application;
use Libs\Core\Http\Request as RequestTool;
use Libs\Core\Store\PrivateStorage;
use Workerman\Protocols\Http;
use Workerman\WebServer;
use Workerman\Worker;
use Libs\Core\Http\Response;

//定义全局常量
define('ROOT', realpath(__DIR__.'/../../'));
//解析配置文件
$envConfig = parse_ini_file(__DIR__ . "/../../.env", true);

//配置web服务器
// web 服务器通过前端仓库代码执行 https://github.com/suhanyujie/NovelSpiderFrontend
//$port = $envConfig['web']['WEB_SITE_PORT'] ?? 8080;
//$web  = new WebServer('http://0.0.0.0:' . $port);
//$web->addRoot($envConfig['web']['host'], __DIR__ . '/../../Frontend/dist');
//$web->count = 3;

//配置接口服务器，用于处理接口访问
$apiPort = $envConfig['web']['API_SITE_PORT'] ?? 8081;
$apiServ = new Worker('http://0.0.0.0:'.$apiPort);
$apiServ->name = 'apiServer';
$apiServ->count = 1;
$iconContent = file_get_contents(__DIR__.'/../../favicon.ico');

PrivateStorage::$container =
$app = new Application();

$apiServ->onWorkerStart = function ()use($app) {
    //加载全局辅助函数
    require_once ROOT.'/Libs/Helper/functions.php';
    //加载路由
    require_once __DIR__.'/../Routes/routes.php';
    //加载和初始化配置相关
    //$app->bind(Router::class, Router::class);
    //初始化数据库连接池

    //初始化服务提供者
    // $app->bind(Router::class, Router::class);
};

$apiServ->onMessage = function ($connection, $data)use($iconContent, &$app) {
    // 针对OPTIONS请求 做处理
    if (isset($data['server']['REQUEST_METHOD']) && $data['server']['REQUEST_METHOD'] == 'OPTIONS') {
        Response::setAccessAllowHeader();
        $connection->send(json_encode([
            'status' => 1,
            'msg'    => 'ok',
        ]));
    }
    if (empty($data['server']['REQUEST_URI'])) {
        $connection->send('404');
        return;
    }
    //判断是否是网站icon
    $ext = pathinfo($data['server']['REQUEST_URI'],PATHINFO_EXTENSION);
    if ($ext === 'ico') {
        Http::header('Content-type:image/icon');
        $connection->send($iconContent);
        return;
    }
    $requestUri = $data['server']['REQUEST_URI'];
    //根据uri解析对应的控制器和方法名称
    $response = (new RequestTool($data))->handleRequest($requestUri);
    if (!is_string($response)) {
        Response::setAccessAllowHeader();
        $response = json_encode($response, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        // Http::header('Content-length:'.strlen($response));
    }

    $connection->send($response);
};

Worker::runAll();
