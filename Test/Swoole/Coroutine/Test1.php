<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/14
 * Time: 上午8:30
 */

namespace Test\Swoole\Coroutine;

require __DIR__."/../../../vendor/autoload.php";

use Swoole\Coroutine as co;
use GuzzleHttp\Client;

class Test1
{
    protected $data = [];

    public function index()
    {
        $startTime = microtime(true);
        go([$this, 'getList']);
//        $this->data['testUrl'] = 'http://www.biquge.com.tw/14_14055/';
//        go([$this, 'getList']);
//        $this->data['testUrl'] = 'http://guzzle-cn.readthedocs.io/zh_CN/latest/quickstart.html';
//        go([$this, 'getList']);
        $endTime = microtime(true);
        $spend = round($endTime - $startTime, 2);
        echo '耗时：' . $spend . PHP_EOL;
    }

    public function getList($paramArr = [])
    {
        $options = [
            'url' => 'http://www.swoole.com/wiki/page/774.html',
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (isset($this->data['testUrl'])) {
            $url = $this->data['testUrl'];
        }
        try{
//            $httpClient = new \GuzzleHttp\Client([
//                'timeout'  => 5,
//            ]);
//            $response = $httpClient->request('get',$url);
//            $response = $response->getBody();
            $cli = new \Swoole\Coroutine\Http\Client('https://www.swoole.com', 443);
            $cli->set(['timeout'=>2,]);
            $cli->get('/wiki/page/774.html');
            $cli->recv();
            $res = $cli->body;
            $cli->close();
        }catch(\Exception $e){
            echo 'error ';
        }
        var_dump((string)$res);
    }
}
$event = new Test1();
$event->index();

