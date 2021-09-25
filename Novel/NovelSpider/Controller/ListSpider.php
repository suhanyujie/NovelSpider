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
    protected $baseUrl = 'http://www.xxbiquge.com/';
    public $mainSelector = '.listmain';
    protected $novelRow = [];


    /**
     * @desc:构造函数
     * @author:Samuel Su(suhanyu)
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
     * @author:Samuel Su(suhanyu)
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
    public function getList()
    {
        $url = $this->mainUrl;
        $rules = [
            'list' => [$this->mainSelector, 'html',],
        ];
        // 因为 `QueryList::html($html)->encoding('UTF-8', 'GBK')` 的编码转换有问题，所以换一种方法转换编码。
        // $hj = QueryList::Query($url, $rules, $this->mainSelector, 'utf-8');
        $html = file_get_contents($url);
        $html = iconv('GBK', 'UTF-8' . '//IGNORE', $html);
        $hj = QueryList::html($html)
            ->rules($rules)
            ->query();
        $dataObj = $hj->getData(function ($aEle) {
            // `range('dd')` 很重要，用于匹配多个结果集（在 n 个区域中，匹配到 n 个结果）。
            $res = QueryList::html($aEle['list'])->rules([
                'link' => ['a', 'href',],
                'title' => ['a', 'text',],
            ])->range('dd')->query()->getData(function ($item, $key) {
                return $item;
            });
            return $res;
        });
        return $dataObj->all();
    }

    /**
     * @desc: 设置 小说主页的url
     * @author:Samuel Su(suhanyu)
     * @date:17/4/26
     * @param String $url
     * @return void
     */
    public function setMainUrl($url) {
        $this->mainUrl = $url;
    }

    /**
     * @desc: 设置列表所在主区域的选择器
     * @author:Samuel Su(suhanyu)
     * @date:17/4/26
     * @param String $selector
     * @return void
     */
    public function setMainAreaSelector($selector) {
        $this->mainSelector = $selector;
    }
} // end of class