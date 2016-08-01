<?php
namespace Novel\NovelSpider\Controller;

use QL\QueryList;
use Predis\Client;
use Novel\NovelSpider\Models\ListModel;


class Test{
    // 只针对 "大宋王候" 的Novel
    protected $baseUrl = 'http://www.biquwu.cc/biquge/17_17308/';
    protected $redisObj = null;
    protected $listUrlKey = 'url-list';

    public function __construct(){
        if(!$this->redisObj){
            $this->redisObj = new \Predis\Client();
            if(!$this->redisObj){
                echo 'redis没有启动吧!'.PHP_EOL;
                return false;
            }
        }
    }
    // 获取列表
    public function getList(){
        $url = 'http://www.biquwu.cc/biquge/17_17308/';
        //$url = 'http://www.zreading.cn/';
        $hj = QueryList::Query($url,
                                array(
                                    "latest"=>array('li:last','html'),
                                    "list"=>array('li','html'),

                                ),
                            '.article_texttitleb','utf-8');
        $data = $hj->getData(function($item){
            $item['list'] = QueryList::Query($item['list'],array(
                'link'=>array('a', 'href','',function($str){
                            return $this->baseUrl.$str;
                        }),
                'title'=>array('a', 'text'),
            ))->data;
            return $item;
        });

        return $data[0]['list'];
    }// end of function

    /**
     * 从redis获取列表
     * @param string $cacheKey redis的key
     * @return array|bool 返回数据列表
     */
    public function getListFromRedis($cacheKey=''){
        if(!$cacheKey)return false;
        $redis = $this->redisObj;
        //$redis -> hmset ( $keyConfig['list-key'] , $list ) ;
        //$redis -> del ( $keyConfig['list-key'] ) ;
        $res = $redis->hgetall($cacheKey);
        return $res;
    }

    /**
     * 通过一个url抓取详情
     */
    public function getDetail($type=2){
        //$listModel = new ListModel();
        $url = $this->getNextUrl($type);
        if(!$url){
            echo "没有url可以抓取详情啦~1".PHP_EOL;
            return false;
        }
        //$url = 'http://www.biquwu.cc/biquge/17_17308/c5056844.html';// test data
        $hj = QueryList::Query($url,
            array(
                "title"=>array('.book_content_text>h1','html'),
                "content"=>array('#book_text','html'),
            ),
            '#mains','UTF-8');
        $data = $hj->getData(function($item){
            return $item;
        });

        return $data[0];
    }

    /**
     * 查询MySQL中的列表url,向redis中push  使用list数据结构
     */
    public function getListFromMysql($type=0){
        if(!$type)return false;
        $lisrModel = new ListModel();
        $res = $lisrModel->getAll(array(
            'novel_id'=>2,
            'flag'=>0,
            'num'=>1000,
        ));

        return $res;
    }
    /**
     * 向redis中lpush数据url
     */
    public function pushIntoRedis($data){
        if(!$data)return false;
        $redis = $this->redisObj;
        // 如果有数据,则不用push
        if($redis->llen($this->listUrlKey)){
            return true;
        }
        $dataIdArr = [];
        // 没有则 将数据push到redis中
        foreach($data as $K=>$v){
            $redis->lpush($this->listUrlKey,$v['url']);
            $dataIdArr[] = $v['id'];
        }
        // push完成之后,将list表中的flag置为1
        $listModel = new ListModel();
        $flag = $listModel->updateMultiple([
            'fieldParam'=>['flag'=>1],
            'conditionParam'=>[
                'id'=>$dataIdArr,
            ],
        ]);


        return true;
    }
    /**
     * 获取下一个可以抓取详情的url
     */
    public function getNextUrl($type=2){
        $redis = $this->redisObj;
        $url = $redis->lpop($this->listUrlKey);
        if(!$url){
            $res = $this->getListFromMysql($type);
            if(!$res){
                echo "Mysql中也没有尚未抓取的url啦~1".PHP_EOL;
                return false;
            }
            $this->pushIntoRedis($res);
        }
        $url = $redis->lpop($this->listUrlKey);
        return $url;
    }
    /**
     * 存储1篇详情
     */
    public function saveDetail($dataOri = []){
        if(!$dataOri)return false;
        $data = [
            ''
        ];
    }// end of function


}// end of class


