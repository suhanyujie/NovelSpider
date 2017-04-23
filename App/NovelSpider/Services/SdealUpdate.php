<?php
/**
 * @desc: 处理是否有最新连载
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/4/23
 * Time: 下午9:40
 */


namespace Novel\NovelSpider\Services;


class SdealUpdate
{
    protected $novelId = 0;

    public function __construct( $novelId ){
        $this->novelId = $novelId;


    }

    /**
     * @desc: 根据小说id,获得网络最新连载
     * @author:Samuel Su(suhanyu)
     * @date:17/4/23
     * @param String $param
     * @return Array
     */
    public function getInternetUpdate($novelId) {

    }



}