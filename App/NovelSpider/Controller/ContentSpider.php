<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/10/23
 * Time: 上午8:40
 */

namespace Novel\NovelSpider\Controller;

use QL\QueryList;

class ContentSpider
{
    /**
     * @desc 通过详情页的url，获取小说内容
     * @param $url
     * @return string
     */
    public function getContent($url='')
    {
        // $url = 'http://www.xxbiquge.com/3_3482/5549506.html';
        $res = QueryList::query($url, [
            'title'=>['.bookname h1', 'text'],
            'content'=>['#content', 'html'],
        ])->getData();
        
        return $res;
    }
}
