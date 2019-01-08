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
$apiServ->count = 2;
$iconContent = file_get_contents(__DIR__.'/../../Frontend/favicon.ico');

$apiServ->onWorkerStart = function () {
    //加载全局辅助函数
    require_once ROOT.'/Libs/Helper/functions.php';
    //加载路由
    require_once __DIR__.'/../Routes/routes.php';
    //加载和初始化配置相关

    //初始化数据库连接池

};

$apiServ->onMessage = function ($connection, $data)use($iconContent) {
    //1.处理 favicon.ico 文件
    if (isset($data['server']['REQUEST_URI']) &&
        strpos($data['server']['REQUEST_URI'],'favicon.ico') !== false)
    {
        $file_size = filesize(__DIR__.'/../../Frontend/favicon.ico');
        Http::header('Content-Type: image/x-icon');
        Http::header("Content-Length: $file_size");
        $connection->send($iconContent);
        return;
    }
    //2.针对请求，路由处理
    $refer = $data['server']['HTTP_REFERER'] ?? '';
    $responseStr = 'hello world!';
//    $result = Router::match(['get','match'],'Access','LoginController@login');
//    if (is_array($result)) {
//        $responseStr = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
//    }

    ob_start();
    Macaw::dispatch();
    $responseStr = ob_get_clean();
    var_dump($responseStr);

    $connection->send($responseStr);
};

Worker::runAll();
