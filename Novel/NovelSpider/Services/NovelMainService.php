<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-05-06
 * Time: 20:05
 */

namespace Novel\NovelSpider\Services;

use Novel\NovelSpider\Models\NovelMainModel;
use QL\QueryList;

/**
 * 小说主体逻辑处理
 * Class NovelMainService
 * @package Novel\NovelSpider\Services
 */
class NovelMainService
{
    public function __construct()
    {

    }

    // 获取小说列表
    public function getMainList($params = []): array
    {
        $option = [
            'page' => 1,
            'size' => 20,
        ];
        $option = array_merge($option, $params);
        $offset = ($option['page'] - 1) * $option['size'];
        $list = (new NovelMainModel)->getList([
            'offset' => $offset,
            'limit' => $option['size'],
        ]);
        if (empty($list)) {
            throw new \Exception("该小说不存在！", -1);
        }
        return [
            'status' => 1,
            'data' => [
                'list' => $list,
            ],
        ];
    }

    /**
     * @desc 通过url获取小说信息
     */
    public function getOneNovelInfoByUrl($url = '')
    {
        $hj = QueryList::get($url)
            ->rules([
                "title"  => ['#book #info h1', 'text'],
                "author" => ['#book #info p:nth-child(4)', 'text'],
            ])
            ->encoding('utf-8', 'gbk')
            ->query(function ($item) {
                //$item['title']  = iconv('gbk', 'UTF-8//IGNORE', $item['title']);
                $item['author'] = str_replace('作    者：', '', $item['author']);
                return $item;
            })
            ->getData();
        $info = $hj->first();
        return $info;
    }
}
