<?php
namespace Novel\NovelSpider\Controller;

use QL\QueryList;
use Predis\Client;
use Novel\NovelSpider\Models\ListModel;


class Test{
    // 只针对 "大宋王候" 的Novel
    protected $baseUrl = 'http://www.biquwu.cc/biquge/17_17308/';
    protected $redisObj = null;

    public function __construct(){
        if(!$this->redisObj){
            $this->redisObj = new \Predis\Client();
        }
        $lisrModel = new ListModel();

        $data = [
            'novel_id'=>1,
            'url'=>'http://laravel.suhanyu.top',
            'flag'=>0,
        ];
        $lisrModel->novel_id = 1;
        $lisrModel->url = 'http://laravel.suhanyu.top';
        $lisrModel->flag = 0;
        $lisrModel->Create();


    }

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


}// end of class


