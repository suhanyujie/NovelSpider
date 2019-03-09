<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/22
 * Time: 上午9:34
 */

require_once __DIR__ . "/../../vendor/autoload.php";

use \Workerman\Worker;
use Workerman\WebServer;
use Workerman\Protocols\Http;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response as ServerResponse;
use Libs\Core\Store\PrivateStorage;
use Libs\Core\Container\Application;
use Libs\Core\Http\Request as RequestTool;

//定义全局常量
define('ROOT', realpath(__DIR__.'/../../'));
//解析配置文件
$envConfig = parse_ini_file(__DIR__ . "/../../.env", true);

//配置web服务器
$port = $envConfig['WEB_SITE_PORT'] ?? 8080;
$web  = new WebServer('http://0.0.0.0:' . $port);
$web->addRoot($envConfig['web']['host'], __DIR__ . '/../../Frontend/dist');
$web->count = 3;

//配置接口服务器，用于处理接口访问
$apiPort = $envConfig['API_SITE_PORT'] ?? 8081;
$apiServ = new Worker('http://0.0.0.0:'.$apiPort);
$apiServ->name = 'apiServer';
$apiServ->count = 1;
$iconContent = file_get_contents(__DIR__.'/../../Frontend/favicon.ico');

PrivateStorage::$container =
$app = new Application();

$apiServ->onWorkerStart = function ()use($app) {
    //加载全局辅助函数
    require_once ROOT.'/Libs/Helper/functions.php';
    //加载路由
    require_once __DIR__.'/../Routes/routes.php';
    //加载和初始化配置相关

    //初始化数据库连接池

    //初始化服务提供者
    // $app->bind(Router::class, Router::class);
};

$apiServ->onMessage = function ($connection, $data)use($iconContent, &$app) {
    var_dump($connection,$data);
    if (empty($data['server']['REQUEST_URI'])) {
        $connection->send('404');
        return;
    }
    //判断是否是网站icon
    $ext = pathinfo($data['server']['REQUEST_URI'],PATHINFO_EXTENSION);
    if ($ext === 'ico') {
        $connection->send($iconContent);
        return;
    }
    $requestUri = $data['server']['REQUEST_URI'];
    //根据uri解析对应的控制器和方法名称
    $response = (new RequestTool)->handleRequest($requestUri);

    $connection->send($response);
};

Worker::runAll();
