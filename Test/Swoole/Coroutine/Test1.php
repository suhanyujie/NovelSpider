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
        go(function () {
            $this->data['testUrl'] = 'http://www.biquge.com.tw/14_14055/9198191.html';
            go([$this, 'getList']);
            $this->data['testUrl'] = 'http://www.biquge.com.tw/14_14055/9198549.html';
            go([$this, 'getList']);
            $this->data['testUrl'] = 'http://www.biquge.com.tw/14_14055/9194140.html';
            go([$this, 'getList']);
            $this->data['testUrl'] = 'http://www.biquge.com.tw/14_14055/9195936.html';
            go([$this, 'getList']);

            echo 'child coroutine...'.PHP_EOL;
        });
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
//            $statusCode = $response->getStatusCode();
//            $body = $response->getBody();
//            $body = (string)$body;
//            echo $statusCode.PHP_EOL;

            $cli = new \Swoole\Coroutine\Http\Client('http://www.biquge.com.tw', 80);
            $cli->setHeaders([
                'Host'            => "http://www.biquge.com.tw",
                "User-Agent"      => 'Chrome/49.0.2587.3',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml',
                'Accept-Encoding' => 'gzip',
            ]);
            $cli->set(['timeout'=>4,]);
            $cli->setDefer();
            $cli->get('/14_14055/9198191.html');
            $cli->recv();
            $res = $cli->statusCode;

            file_put_contents('test.log',(string)$res);
            return;
        }catch(\Exception $e){
            echo 'error ';
        }
    }
}
$event = new Test1();
$event->index();

