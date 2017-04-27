<?php
/**
 * @desc: 抓取列表
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/4/26
 * Time: 上午8:16
 */


namespace Novel\NovelSpider\Controller;

use QL\QueryList;

class ListSpider
{
    public $mainUrl = 'http://www.xxbiquge.com/3_3482/';
    public $mainSelector = '#list';

    /**
     * @desc:构造函数
     * @author:Samuel Su(suhanyu)
     * @date:17/4/26
     * @param String $param
     * @return void
     */
    public function __construct() {

    }

    // 获取列表
    public function getList(){
        $url = $this->mainUrl;
        $rules = [
            'list'=>[
                'dd',
                'html',
            ],
        ];
        $hj = QueryList::Query($url, $rules, $this->mainSelector,'utf-8');
        $data = $hj->getData(function($aEle){
            $res = [];
            $res['allChaper'] = QueryList::query($aEle['list'],
                [
                    'linkUrl'=>[
                        'a','href',
                    ],
                    'title'=>[
                        'a','text',
                    ]
                ]
            )->data;
            return $res;
        });

        return $data[0]['allChaper'];
    }// end of function



    /**
     * @desc: 设置 小说主页
     * @author:Samuel Su(suhanyu)
     * @date:17/4/26
     * @param String $param
     * @return void
     */
    public function setMainUrl($url) {
        $this->mainUrl = $url;
    }

    /**
     * @desc: 设置列表所在主区域的选择器
     * @author:Samuel Su(suhanyu)
     * @date:17/4/26
     * @param String $param
     * @return void
     */
    public function setMainAreaSelector($selector) {
        $this->mainSelector = $selector;
    }




} // end of class