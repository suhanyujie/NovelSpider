<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/7/22
 * Time: 18:04
 */

namespace Novel\Controllers\Spider;

use Novel\Controllers\Controller;
use Novel\NovelSpider\Services\NovelService;
use Novel\NovelSpider\Models\NovelMainModel;
use Novel\NovelSpider\Models\NovelContentModel;
use Novel\NovelSpider\Services\Common\Config\ConfigService;

/**
 * 接口类文件
 * Class ApiSpider
 * @package Novel\NovelSpider\Controller
 */
class ApiSpiderController extends Controller
{
    /**
     * @var NovelMainModel
     */
    protected $mainListModel;

    public function __construct()
    {
        parent::__construct();
        if (!$this->mainListModel) {
            $this->mainListModel = new NovelMainModel;
        }
    }

    /**
     * 小说列表 一个小说表示一条数据
     * /Spider/ApiSpider/mainList
     */
    public function mainList()
    {
        $paramArr = [
            'offset' => 0,
            'limit'  => 10,
        ];
        $result = $this->mainListModel->getList($paramArr);
        $returnArr = [
            'code' => 200,
            'data' => $result,
        ];
        return $this->json($returnArr);
    }

    /**
     * 小说简介 信息 介绍
     * /Spider/ApiSpider/desc
     */
    public function desc()
    {

    }

    /**
     * 章节列表
     * /Spider/ApiSpider/list
     */
    public function list()
    {

    }

    /**
     * 章节详情
     * /Spider/ApiSpider/detail
     */
    public function detail()
    {

    }
}
