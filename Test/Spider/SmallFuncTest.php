<?php
/**
 * @desc: 爬虫小模块的测试
 */


namespace Test\Spider;

use Novel\NovelSpider\Services\NovelService;
use PHPUnit\Framework\TestCase;
use Novel\NovelSpider\Controller\ListSpider;

require("../../vendor/autoload.php");

// some init
$envConfig = parse_ini_file(__DIR__ . "/../../.env", true);

class SmallFuncTest extends TestCase
{
    public function testListSpider()
    {
        $input = [
            'base_url'=>'https://www.bbiquge.net',
            'list_url'=>'https://www.bbiquge.net/book_30825/',
        ];
        $service = new ListSpider($input);
        $res = $service->getList();
        // 索引数组,并且单元个数超过10
        $this->assertIsArray($res, "getList error");
    }

    public function testNovelService()
    {
        $service = new NovelService();
        $res = $service->getList();
        // 索引数组,并且单元个数超过10
        $this->assertIsArray($res, "getList error");
    }

    // 测试获取内容详情
    public function testNovelDetail()
    {
        $service = new NovelService();
        $res = $service->getDetail([
            'url' => 'http://www.biqigewx.com/1_1383/999165.html',
            'chapter'=> "chapter 001"
        ]);
        // 索引数组,并且单元个数超过10
        $this->assertEquals(1, $res['status'], "getDetail error");
    }
}//  end of class
