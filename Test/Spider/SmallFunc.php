<?php
/**
 * @desc: 爬虫小模块的 测试
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/4/24
 * Time: 上午8:45
 */

//include "/www/www/vendor/autoload.php";

namespace Test\Spider;

use PHPUnit\Framework\TestCase;
use Novel\NovelSpider\Controller\ListSpider;

class SmallFunc extends TestCase
{

    public function testListSpider()
    {
        $service = new ListSpider();
        $service->getList();
    }


}//  end of class