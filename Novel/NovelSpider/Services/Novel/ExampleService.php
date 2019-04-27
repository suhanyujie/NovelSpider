<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/4/27
 * Time: 13:01
 */

namespace Novel\NovelSpider\Services\Novel;

use Novel\NovelSpider\Controller\ListStore;
use QL\QueryList;

class ExamleService
{
    public $mainUrl = 'http://www.xxbiquge.com/3_3482/';
    protected $baseUrl = 'http://www.xxbiquge.com/';
    public $mainSelector = '#list';
    protected $novelRow = [];


    /**
     * @desc:构造函数
     * @author:Samuel
     * @date:17/4/26
     * @param array $novelRow
     *      $novelRow = [
     *          'list_url'=>列表url （必填）
     *          'base_url'=>根路径 （必填）
     *      ];
     * @return void
     */
    public function __construct($novelRow) {
        $this->novelRow = $novelRow;
        $this->mainUrl = $novelRow['list_url'];
        $this->baseUrl = $novelRow['base_url'];
    }

    /**
     * @desc: 获取并存储下列表
     * @author:Samuel
     * @date:17/5/2
     * @param void
     * @return void
     */
    public function runList() {
        $dataArr = $this->getList();
        $storeObj = new ListStore();

        $storeObj->storeAll( $this->novelRow['id'], $storeObj );
    }

    /**
     * @desc 获取列表
     * @return mixed
     */
    public function getList(){
        $url = $this->mainUrl;
        $rules = [
            'list' => [
                'dd',
                'html',
            ],
        ];
        $hj = QueryList::Query($url, $rules, $this->mainSelector,'utf-8');
        $data = $hj->getData(function ($aEle) {
            $res = [];
            $res['allChaper'] = QueryList::query($aEle['list'], [
                'link'  => [
                    'a',
                    'href',
                ],
                'title' => [
                    'a',
                    'text',
                ],
            ])->data;

            return $res;
        });

        return $data[0]['allChaper'];
    }// end of function



    /**
     * @desc: 设置 小说主页的url
     * @author:Samuel
     * @date:17/4/26
     * @param String $url
     * @return void
     */
    public function setMainUrl($url) {
        $this->mainUrl = $url;
    }

    /**
     * @desc: 设置列表所在主区域的选择器
     * @author:Samuel
     * @date:17/4/26
     * @param String $selector
     * @return void
     */
    public function setMainAreaSelector($selector) {
        $this->mainSelector = $selector;
    }
}
