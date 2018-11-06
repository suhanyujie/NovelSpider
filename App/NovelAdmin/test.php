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

$encConfig = parse_ini_file(__DIR__ . "/../../.env");

$port = isset($encConfig['WEB_SITE_PORT'])&&$encConfig['WEB_SITE_PORT'] ? $encConfig['WEB_SITE_PORT'] : 8080;
$web = new WebServer('http://0.0.0.0:'.$port);
$web->addRoot('suhy.zyw.com',__DIR__.'/../../Frontend/dist');
$web->count = 3;

Worker::runAll();
