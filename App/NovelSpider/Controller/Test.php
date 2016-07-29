<?php

namespace Novel\NovelSpider\Controller;

use QL\QueryList;
use Predis\Client;

class Test{
    // 只针对 "大宋王候" 的Novel
    protected $baseUrl = 'http://www.biquwu.cc/biquge/17_17308/';
    protected $redisObj = null;

    public function __construct(){
        if(!$this->redisObj){
            $this->redisObj = new \Predis\Client();
        }
    }

    public function getList(){
        $hj = QueryList::Query('http://www.biquwu.cc/biquge/17_17308/',
                                array(
                                    "latest"=>array('.article_texttitleb li:last','html'),
                                    "list"=>array('.article_texttitleb li','html'),

                                ),
                            'body','utf-8');
        $data = $hj->getData(function($x){
            return $x['list'];
        });
        //print_r($data);
        return $data;
    }

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


