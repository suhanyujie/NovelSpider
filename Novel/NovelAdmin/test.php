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
use Libs\Core\Route\Router;
use NoahBuscher\Macaw\Macaw;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Libs\Core\Container\Application;

//定义全局常量
define('ROOT', realpath(__DIR__.'/../../'));
//解析配置文件
$envConfig = parse_ini_file(__DIR__ . "/../../.env", true);

//配置web服务器
$port = $envConfig['WEB_SITE_PORT'] ?? 8080;
$web  = new WebServer('http://0.0.0.0:' . $port);
$web->addRoot($envConfig['web']['host'], __DIR__ . '/../../Frontend/dist');
$web->count = 3;
//$web->protocol = 'http';

//配置接口服务器，用于处理接口访问
$apiPort = $envConfig['API_SITE_PORT'] ?? 8081;
$apiServ = new Worker('tcp://0.0.0.0:'.$apiPort);
$apiServ->name = 'apiServer';
$apiServ->count = 2;
$iconContent = file_get_contents(__DIR__.'/../../Frontend/favicon.ico');

$app = new Application();

$apiServ->onWorkerStart = function ()use($app) {
    //加载全局辅助函数
    require_once ROOT.'/Libs/Helper/functions.php';
    //加载路由
    require_once __DIR__.'/../Routes/routes.php';
    //加载和初始化配置相关

    //初始化数据库连接池

    //初始化服务提供者


};

$apiServ->onMessage = function ($connection, $data)use($iconContent, &$app) {
    //通过http数据，实例化符合psr7的Request
    $request = \GuzzleHttp\Psr7\parse_request($data);
    //1.处理 favicon.ico 文件
    if (
        strpos($request->getUri()->getPath(),'favicon.ico') !== false
    ) {
        $file_size = filesize(__DIR__.'/../../Frontend/favicon.ico');
        Http::header('Content-Type: image/x-icon');
        Http::header("Content-Length: $file_size");
        $connection->send($iconContent);
        return;
    }
    $app->bind(Request::class, function()use($data) {
        $request = \GuzzleHttp\Psr7\parse_request($data);
        //初始化全局变量 $_SERVER
        $_SERVER['REQUEST_URI'] = $request->getUri()->getPath();
        $_SERVER['REQUEST_METHOD'] = $request->getMethod();
        $_SERVER['SERVER_PROTOCOL'] = $request->getProtocolVersion();

        return $request;
    });

    $app->bind(Response::class, function()use($data) {
        return new Response(200,
                                    [],
                                    "hello world..."
                            );
    });
    $loginObj = $app->make(\Novel\Controllers\Access\LoginController::class);

    //针对请求，路由处理
    $refer = $data['server']['HTTP_REFERER'] ?? '';
    $responseStr = 'hello world!';
//    $result = Router::match(['get','match'],'Access','LoginController@login');
//    if (is_array($result)) {
//        $responseStr = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
//    }

    ob_start();
    Macaw::dispatch();
    $responseStr = ob_get_clean();

    $connection->send($responseStr);
    var_dump($responseStr);
};

Worker::runAll();
