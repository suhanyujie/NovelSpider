<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/8/17
 * Time: 15:29
 */

namespace Test\NovelTest;

use Novel\NovelSpider\Services\Novel\StatusChangeService;
use PHPUnit\Framework\TestCase;

class TestBasic extends TestCase
{
    public function testsetStatusChapterDetailCollected()
    {
        try {
            StatusChangeService::setStatusChapterDetailCollected(1);
        } catch (\Exception $e) {
            echo $e->getMessage();
            self::assertTrue(false, $e->getMessage());
        }
        self::assertTrue(true);
    }

    public function testsetChapterListAlready()
    {
        try {
            StatusChangeService::setChapterListComplete(1);
        } catch (\Exception $e) {
            echo $e->getMessage();
            self::assertTrue(false, $e->getMessage());
        }
        self::assertTrue(true);
    }
}
