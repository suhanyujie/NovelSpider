<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Predis\Client as PredisClient;
use Libs\Helper\Traits\HttpRequst as HttpRequstTrait;

//docker-elasticsearch的测试
class Es
{
    use HttpRequstTrait;

    /**
     * @desc 向es中新增数据
     */
    public function add()
    {
        $uriPath = '/test/article/1';
        $queryBody = '{
    "first_name" : "John",
    "last_name" :  "Smith",
    "age" :        25,
    "about" :      "I love to go rock climbing",
    "interests": [ "sports", "music" ]
}';
        $fullUrl = 'http://127.0.0.1:9200'.$uriPath;
        $result = $this->httpRequest([
            'url'    => $fullUrl,
            'method' => 'put',//
            'header' => ['content-type'=>'application/json'],
            'body'   => $queryBody,
        ]);
        var_dump($result);
    }

    /**
     * @desc 查询
     */
    public function query()
    {
        $uriPath = '/test/article/_search';
        $searchParam = [
            'query'=>[
                'bool'=>[
                    'must'=>[
                        'match'=>[
                            'about'=>'love',
                        ],
                    ],
                    'filter'=>[
                        'range'=>[
                            'age'=>[
                                'gt'=>21,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $searchParam = json_encode($searchParam, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $queryBody = $searchParam;
        $fullUrl = 'http://127.0.0.1:9200'.$uriPath;
        $result = $this->httpRequest([
            'url'    => $fullUrl,
            'method' => 'get',//
            'header' => ['content-type'=>'application/json'],
            'body'   => $queryBody,
        ]);
        var_dump($result);exit(PHP_EOL.'下午4:11'.PHP_EOL);
    }
}
//执行查询
(new Es)->query();

exit();
//docker-redis的测试
$pr = new PredisClient([
    'host'=>'127.0.0.1',
    'port'=>6333,
]);
$cacheKey = 'testKey_123';
//$pr->set($cacheKey,"who are u...");
var_export($pr->get($cacheKey));
var_dump($pr->isConnected());exit(PHP_EOL.'下午2:30'.PHP_EOL);

$a = [
    12312312
];
$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'http://httpbin.org',
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);

var_dump($client);







