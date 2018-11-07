<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/22
 * Time: 上午9:34
 */

require __DIR__ . "/../../vendor/autoload.php";

use \Workerman\Worker;
use Workerman\WebServer;
use Workerman\Protocols\Http;
use Libs\Core\Route\Router;

$encConfig = parse_ini_file(__DIR__ . "/../../.env");

$port = isset($encConfig['WEB_SITE_PORT'])&&$encConfig['WEB_SITE_PORT'] ? $encConfig['WEB_SITE_PORT'] : 8080;
$web = new WebServer('http://0.0.0.0:'.$port);
$web->addRoot('suhy.zyw.com',__DIR__.'/../../Frontend/dist');
$web->count = 3;

//接口访问
//针对请求做处理
$apiPort = isset($encConfig['API_SITE_PORT'])&&$encConfig['API_SITE_PORT'] ? $encConfig['API_SITE_PORT'] : 8081;
$apiServ = new Worker('http://0.0.0.0:'.$apiPort);
$apiServ->count = 2;
$iconContent = file_get_contents(__DIR__.'/../../Frontend/favicon.ico');
$apiServ->onMessage = function ($connection, $data)use($iconContent) {
    //处理 favicon.ico 文件
    if (isset($data['server']['REQUEST_URI']) &&
        strpos($data['server']['REQUEST_URI'],'favicon.ico') !== false)
    {
        $file_size = filesize(__DIR__.'/../../Frontend/favicon.ico');
        Http::header('Content-Type: image/x-icon');
        Http::header("Content-Length: $file_size");
        $connection->send($iconContent);
        return;
    }
    //此处，针对请求数据，路由匹配
    $url = $data['server']['HTTP_REFERER'];
    Router::match(['get','match'],'/Access/Login','Access\LoginController@login');

    var_dump($connection, $data);
    $connection->send('hello world!');
};

Worker::runAll();
