<?php
/**
 * @desc: 爬虫小模块的 测试
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/4/24
 * Time: 上午8:45
 */


namespace Test\Spider;

use PHPUnit\Framework\TestCase;
use Novel\NovelSpider\Controller\ListSpider;

include("/www/www/vendor/autoload.php");

class SmallFunc extends TestCase
{
    public function testListSpider()
    {
        $service = new ListSpider();
        $res = $service->getList();
        // 索引数组,并且单元个数超过10
        $this->assertArrayHasKey(10, $res);
    }


}//  end of class