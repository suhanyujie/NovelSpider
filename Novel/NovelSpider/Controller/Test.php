<?php

namespace Novel\NovelSpider\Controller;

use Novel\NovelSpider\Models\NovelContentModel;
use QL\QueryList;
use Novel\NovelSpider\Models\ListModel;
use Libs\Helper\NumberTransfer;
use Novel\NovelSpider\Models\NovelListModel;
use Novel\NovelSpider\Models\NovelMainModel;
use Novel\NovelSpider\Services\DataCacheService;

/**
 * Class Test
 * @package Novel\NovelSpider\Controller
 */
class Test
{
    // 只针对 "大宋王候" 的Novel
    protected $baseUrl = 'http://www.biquwu.cc/biquge/17_17308/';
    protected $redisObj = null;
    protected $listUrlKey = 'novel-list-key';

    protected $data = [];

    public function __construct()
    {
        if (!$this->redisObj) {
            $envConfigArr = DataCacheService::get('envConfigArr');
            if (!isset($envConfigArr['redis_config'])) {
                throw new \Exception("has no config data in your env file.", -100);
            }
            $redisConfig = $envConfigArr['redis_config'];
            $backupParam    = [
                'host'     => $redisConfig['HOST'] ?? '127.0.0.1',
                'port'     => $redisConfig['PORT'] ?? 6379,
                'database' => 0,
            ];
            $redis          = new \Predis\Client();
            $this->redisObj = $redis;
            if (!$this->redisObj) {
                echo 'redis没有启动吧!' . PHP_EOL;
                return false;
            }
        }
    }

    // 获取列表
    public function getList()
    {
        $url = 'http://www.biquwu.cc/biquge/17_17308/';
        //$url = 'http://www.zreading.cn/';
        $hj   = QueryList::Query($url,
            array(
                "latest" => array('li:last', 'html'),
                "list"   => array('li', 'html'),
            ),
            '.article_texttitleb', 'utf-8');
        $data = $hj->getData(function ($item) {
            $item['list'] = QueryList::Query($item['list'], array(
                'link'  => array('a', 'href', '', function ($str) {
                    return $this->baseUrl . $str;
                }),
                'title' => array('a', 'text'),
            ))->data;
            return $item;
        });

        return $data[0]['list'];
    }// end of function

    /**
     * @desc 获取所有需要抓取的小说
     * @param array $paramArr
     * @return collect
     */
    public function getNovelList($paramArr = [])
    {
        $options = [
            'where'=>[],
            'novel_status' => '',//
            'fields'       => '*',// string 查询字段
            'isCount'      => '',// 可选：1 是否只返回数据的数量
            'debug'        => '',// 可选：1 调试，为true时，打印出sql语句
            'offset'       => 0,// 可选 int mysql查询数据的偏移量
            'limit'        => 1,// 可选 int mysql查询数据的条数限制
        ];
        $options = array_merge($options, $paramArr);
        $model = new NovelMainModel();
        if (empty($options['fields'])) {
            $model = $model->select($options['*']);
        }
        if (!empty($novel_status)) {
            $model = $model->where('novel_status', $novel_status);
        }
        if (!empty($options['where'])) {
            $model->where($options['where']);
        }
        if (!empty($isCount)) {
            return $model->count();
        }
        //order
        if (!empty($order)) {
            foreach ($order as $orderField => $orderDir) {
                $model = $model->orderby($orderField, $orderDir);
            }
        } else {
            $model = $model->orderby('id', 'desc');
        }
        $model = $model->offset($options['offset'])->limit($options['limit']);
        if (!empty($debug)) {
            echo $model->toSql();
            exit();
        }
        $data = $model->get([$options['fields']]);

        return $data;
    }

    /**
     * 从redis获取列表
     * @param string $cacheKey redis的key
     * @return array|bool 返回数据列表
     */
    public function getListFromRedis($cacheKey = '')
    {
        if (!$cacheKey) return false;
        $redis = $this->redisObj;
        //$redis -> hmset ( $keyConfig['list-key'] , $list ) ;
        //$redis -> del ( $keyConfig['list-key'] ) ;
        $res = $redis->hgetall($cacheKey);
        return $res;
    }

    /**
     * 通过一个url抓取详情
     * @param array $taskData
     * @return bool
     */
    public function getDetail($taskData, $type = 2)
    {
        if (empty($taskData)) {
            $message = date('Y-m-d H:i:s') . "-->没有url可以抓取详情啦~" . PHP_EOL;
            return ['status' => 8300, 'message' => $message];
        }
        $url = $taskData['url'];
        $rule = [
            "content" => ['#content', 'text'],// 小说内容
        ];
        $qr = QueryList::get($url, null, ['connect_timeout'=>30,])->rules($rule)
            ->encoding('UTF-8','GB2312')
            ->removeHead()
            ->query();
        $data = (array)$qr->getData();
        // 一维数组的键是`*items`，因此降维处理
        $data = array_pop($data);
        $content = $data[0]['content'];

        return ['status' => 1, 'data' => $content, 'message' => '获取详情成功！'];
    }

    /**
     * 小说详情内容的新增或更新
     * @param array $paramArr
     * @return array
     */
    public function detailInsertOrUpdate($paramArr = [])
    {
        $options = [
            'where' => [],//如果是新增，则where值可以为空；如果是更新，则where值为数组，例如 [ ['id','=','21']  ]
            'data'  => [],
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        if (isset($this->data['novelContentModel'])) {
            $model = $this->data['novelContentModel'];
        } else {
            $model = $this->data['novelContentModel'] = new NovelContentModel();
        }
        if (!empty($options['where'])) {
            foreach ($options['where'] as $option) {
                $model = $model->where($option[0], $option[1], $option[2]);
            }
            $existObj = $model->get()->first();
            if (!is_null($existObj)) {
                foreach ($options['where'] as $option) {
                    $model = $model->where($option[0], $option[1], $option[2]);
                }
                $result  = $model->update($options['data']);
                $message = '更新';
            } else {
                $result  = $model->create($options['data']);
                $message = '新增';
            }
        } else {
            $result  = $model->create($options['data']);
            $message = '新增';
        }

        if (empty($result)) {
            return ['status' => 2, 'message' => $message . '失败！', 'data' => $result,];
        } else {
            return ['status' => 1, 'message' => $message . '成功！', 'data' => $result,];
        }
    }

    /**
     * 从另一个worker进程中获取taskData
     */
    public function requestTaskDataFromProcess($taskConnection, $data = ['count' => 0,])
    {
        // 任务及参数数据
        $task_data = array(
            'function' => 'send_mail',
            'args'     => array('from' => 'detail task', 'to' => 'list task', 'contents' => 'get-detail'),//refresh-list
        );
        $task_data = $data ? $data : $task_data;
        //var_dump($data);

        $taskConnection->send(json_encode($task_data));
        // 执行异步链接 !!! important
        $taskConnection->connect();
        return true;
    }

    /**
     * 查询MySQL中的列表url,向redis中push  使用list数据结构
     */
    public function getListFromMysql($novelId = 0)
    {
        if (!$novelId) return false;
        if (isset($this->data['listModel'])) {
            $listModel = $this->data['listModel'];
        } else {
            $this->data['listModel'] =
            $listModel = new NovelListModel();
        }
        $res = $listModel->getList([
            'novel_id' => $novelId,
            'flag'     => 2,//2未抓取；1抓取过
            'limit'    => 1000,
        ]);

        return $res;
    }

    /**
     * 向redis中lpush数据url
     */
    public function pushIntoRedis($data)
    {
        if ($data->count() < 1) return false;
        $redis = $this->redisObj;
        // 如果有数据,则不用push
        $queueTaskLen = $redis->llen($this->listUrlKey);
        if ($queueTaskLen > 0) {
            return ['status' => 1, 'message' => 'redis队列中已经有数据，无需入队！'];
        }
        $dataIdArr = [];
        // 没有则 将数据push到redis中
        foreach ($data as $k => $v) {
            $redis->lpush($this->listUrlKey, json_encode($v));
            $dataIdArr[] = $v['id'];
            echo "列表{$v['id']}已经加入队列\n";
        }
        // push完成之后,将list表中的flag置为1
        $listModel    = new NovelListModel();
        $updateResult = $listModel->whereIn('id', $dataIdArr)->update([
            'flag' => 1,
        ]);
        if (!$updateResult) {
            return ['status' => 4, 'message' => 'flag标记更新失败！'];
        }

        return ['status' => 1, 'message' => 'flag标记更新成功！'];
    }

    /**
     * 获取下一个可以抓取详情的url
     */
    public function getNextTaskData($type = 2)
    {
        $redis    = $this->redisObj;
        $taskData = $redis->lpop($this->listUrlKey);
        if (!$taskData) {
            $res = $this->getListFromMysql($type);
            if (!$res) {
                echo "Mysql中也没有尚未抓取的url啦~1" . PHP_EOL;
                return false;
            }
            $this->pushIntoRedis($res);
            $taskData = $redis->lpop($this->listUrlKey);
        }
        return $taskData;
    }

    /**
     * 存储1篇详情
     */
    public function saveDetail($contentData = [])
    {
        if (empty($contentData)) return ['status' => 2, 'message' => '参数缺省$contentData！'];
        if (isset($this->data['detailModel'])) {
            $model = $this->data['detailModel'];
        } else {
            $model = $this->data['detailModel'] = new NovelContentModel();
        }
        $createResult = $model->create($contentData);
        if (!$createResult) {
            return ['status' => 3, 'message' => '保存失败！'];
        } else {
            return ['status' => 1, 'message' => '保存失败！'];
        }
    }// end of function

    /**
     * 获取历史抓取的最新的一章
     */
    public function getLatestChapter()
    {
        $listModel        = new ListModel();
        $res              = $listModel->getAll([
            'order' => 1,
            'num'   => 1,
        ]);
        $res              = $res[0];
        $ourNewestChapter = $res['chapter'];
        $res              = $this->checkHasCrawling($res['chapter']);
    }

    /**
     * 检查这个url/id是否被爬取过
     */
    public function checkHasCrawling($chapter)
    {
        $listModel = new NovelContentModel();
        $res       = $listModel->getAll([
            'chapter' => $chapter,
            'order'   => 1,
            'num'     => 1,
        ]);
        return $res ? true : false;
    }

    /**
     * 保存列表页到mysql
     */
    public function saveList()
    {
        $list      = $this->getList();
        $listModel = new ListModel();
        $i         = 0;
        foreach ($list as $k => $v) {
            $data = [
                'novel_id' => 2,// 2 大宋王侯
                'url'      => $v['link'],
                'title'    => $v['title'],
                'chapter'  => NumberTransfer::checkNatInt($v['title']),
                'flag'     => 0,
            ];
            $flag = $listModel->insertData($data);
        }
        return $flag;
    }
}// end of class
