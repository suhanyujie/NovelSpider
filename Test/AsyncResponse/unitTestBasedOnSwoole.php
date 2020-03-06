<?php
/**
 * 基于 swoole 的单元测试
 */

class CustomerTest
{
    /**
     * 执行单个单元测试
     *  执行一个测试单元就是测试器的 run 方法
     *  run 方法的参数如何定义？如何传参？
     *      使用 curl 方式时，参数是 url，校验使用”闭包“
     */
    public function runOneTest($index = 0)
    {
        co::sleep(1);
        echo "hello test {$index}\n";
    }

}

$testObj = (new CustomerTest());

interface Executor 
{
    public function run();

    public function assert($mixed);
}

// 使用 bash curl 调用进行接口测试
class Unit1Exec implements Executor
{
    public function run()
    {
        $cmd = "";
        exec($cmd, $output, $code);
        $output = implode("\n", $output);

        return $output;
    }

    public function assert($mixed)
    {

    }
}

// 使用 PHP curl 调用进行接口测试
class Unit2Exec implements Executor
{
    public function run($url = '')
    {
        $this->data['result'] = $this->get($url);
        return $this;
    }

    public function assert($mixed)
    {
        return true;
    }

    protected function get($url = '')
    {
        return $this->curl($url, 'GET', [], []);
    }

    protected function curl($url, $httpMethod, $headers, $postFields, $timeout = 3)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $httpMethod);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        if ($timeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }
        //https request
        if (strlen($url) > 5 && stripos($url, 'https') === 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $httpResponse = new \stdClass();
        $httpResponse->body = curl_exec($ch);
        $httpResponse->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            throw new \Exception('Server unreachable: Errno: ' . curl_errno($ch) . ' ' . curl_error($ch));
        }
        curl_close($ch);
        return $httpResponse;
    }
}

// 使用 PHP curl 调用进行接口测试
class Unit3Exec implements Executor
{
    public function run($url = '')
    {
        $host = parse_url($url, PHP_URL_HOST);
        return $this->get($host, '/');
    }
    
    public function assert($mixed)
    {

    }

    protected function get($host = '', $path = '/')
    {
        $cli = new Swoole\Coroutine\Http\Client($host, 80);
        $cli->get($path);
        $body = $cli->body;
        $cli->close();
        return $body;
    }

    protected function request($url = '', $method = 'GET', $header = [], $postData = [])
    {
        
    }
}

$t1 = microtime(true);
$chan = new Swoole\Coroutine\Channel(10);
for ($i = 0;$i<2;$i++) {
    go(function ()use($i, $chan) {
        $result = (new Unit3Exec)->run('https://www.baidu.com');
        $chan->push(['html'=>'1231231',]);
    });
}
$t2 = microtime(true);
$diffTime = round($t2 - $t1, 2);
go(function()use($chan){
    while (1) {
        if ($chan->isEmpty()) {
            co::sleep(1);
            echo "sleep 1\n";
        } else {
            $data = $chan->pop();
            // to do something
            var_dump($data);
        }
    }
});
var_dump($diffTime);
