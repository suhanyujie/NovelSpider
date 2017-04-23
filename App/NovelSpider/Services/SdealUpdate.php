<?php
/**
 * @desc: 处理是否有最新连载
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/4/23
 * Time: 下午9:40
 */


namespace Novel\NovelSpider\Services;

use Libs\Db\Db;
use QL\QueryList;

class SdealUpdate
{
    protected $novelId = 0;

    protected $listUrl = '';

    public function __construct( $novelId ){
        $this->novelId = $novelId;
        // 获取url
        $this->getListUrl();

    }

    /**
     * @desc: 根据小说id,获得网络最新连载
     * @author:Samuel Su(suhanyu)
     * @date:17/4/23
     * @param String $param
     * @return Array
     */
    public function getInternetUpdate($novelId) {
//        QueryList::
    }


    /**
     * @desc: 获取该小说的主页
     * @author:Samuel Su(suhanyu)
     * @date:17/4/23
     * @param String $param
     * @return string
     */
    public function getListUrl() {
        $dbObj = Db::instance('db1');
        $url = $dbObj->select('list_url')->from('novel_main')->where('id='.$this->novelId)->limit(1)->query();
        $this->listUrl = $url[0]['list_url'];
        return $url[0]['list_url'];
    }



}