<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-04-28
 * Time: 11:30
 */

namespace Novel\NovelSpider\Services\Novel;


use Novel\NovelSpider\Services\NovelService;

class OutputService
{
    /**
     * @desc 获取 novel
     */
    public function getNovel($id = 0)
    {
        if (empty($id)) return [];
        $novelService = new NovelService();
    }
}
