<?php
$str = null;
$arr = (array)$str;
$arr = array_pop($arr);
var_dump($arr);
die;

$arr = [12, 9, 10, 43, 78, 54, 9];
function insertSort($arr = []) 
{
    if (empty($arr)) {
        return $arr;
    }
    for ($i=1; $i < count($arr); $i++) { 
        $compNum = $arr[$i];
        for ($j=$i-1; $j >= 0; $j--) { 
            if ($arr[$j] > $arr[$j + 1]) {
                $tmp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $tmp;
            }
        }
    }
    return $arr;
}
var_dump(insertSort($arr));
die;

exit();

//require 'vendor/autoload.php';
//
//use GuzzleHttp\Client;
//use Predis\Client as PredisClient;
//use Libs\Helper\Traits\HttpRequst as HttpRequstTrait;

//导出csv大文件测试
articleAccessLog();

/**
 * 文章访问日志
 * 下载的日志文件通常很大, 所以先设置csv相关的Header头, 然后打开
 * PHP output流, 渐进式的往output流中写入数据, 写到一定量后将系统缓冲冲刷到响应中
 * 避免缓冲溢出
 */
function articleAccessLog($timeStart=0, $timeEnd=1)
{
    set_time_limit(0);
    $columns     = [
        '文章ID', '文章标题'
    ];
    $csvFileName = 'test' . $timeStart . '_' . $timeEnd . '.csv';
    //设置好告诉浏览器要下载excel文件的headers
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    $fp = fopen('php://output', 'a');//打开output流
    mb_convert_variables('GBK', 'UTF-8', $columns);
    fputcsv($fp, $columns);//将数据格式化为CSV格式并写入到output流中
    $perSize   = 1000000;//每次查询的条数
    for ($i = 1; $i <= $perSize; $i++) {
        $rowData = [
            $i, 'title'.$i
        ];
        fputcsv($fp, $rowData);
        unset($rowData);//释放变量的内存
        //刷新输出缓冲到浏览器
        ob_flush();
        flush();//必须同时使用 ob_flush() 和flush() 函数来刷新输出缓冲。
    }
    fclose($fp);
}

exit('-----end----');

//docker-elasticsearch的测试
class Es
{
    use HttpRequstTrait;

    /**
     * @desc 向es中新增数据
     */
    public function add()
    {
        $uriPath   = '/test/article/1';
        $queryBody = '{
    "first_name" : "John",
    "last_name" :  "Smith",
    "age" :        25,
    "about" :      "I love to go rock climbing",
    "interests": [ "sports", "music" ]
}';
        $fullUrl   = 'http://127.0.0.1:9200' . $uriPath;
        $result    = $this->httpRequest([
            'url'    => $fullUrl,
            'method' => 'put',//
            'header' => ['content-type' => 'application/json'],
            'body'   => $queryBody,
        ]);
        var_dump($result);
    }

    /**
     * @desc 查询
     */
    public function query()
    {
        $uriPath     = '/test/article/_search';
        $searchParam = [
            'query' => [
                'bool' => [
                    'must'   => [
                        'match' => [
                            'about' => 'love',
                        ],
                    ],
                    'filter' => [
                        'range' => [
                            'age' => [
                                'gt' => 21,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $searchParam = json_encode($searchParam, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $queryBody   = $searchParam;
        $fullUrl     = 'http://127.0.0.1:9200' . $uriPath;
        $result      = $this->httpRequest([
            'url'    => $fullUrl,
            'method' => 'get',//
            'header' => ['content-type' => 'application/json'],
            'body'   => $queryBody,
        ]);
        var_dump($result);
        exit(PHP_EOL . '下午4:11' . PHP_EOL);
    }
}

//执行查询
(new Es)->query();

exit();
//docker-redis的测试
$pr       = new PredisClient([
    'host' => '127.0.0.1',
    'port' => 6333,
]);
$cacheKey = 'testKey_123';
//$pr->set($cacheKey,"who are u...");
var_export($pr->get($cacheKey));
var_dump($pr->isConnected());
exit(PHP_EOL . '下午2:30' . PHP_EOL);

$a      = [
    12312312
];
$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'http://httpbin.org',
    // You can set any number of default request options.
    'timeout'  => 2.0,
]);

var_dump($client);
