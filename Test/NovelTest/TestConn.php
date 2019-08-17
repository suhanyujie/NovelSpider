<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/8/17
 * Time: 14:50
 */

namespace Test\NovelTest;

use PHPUnit\Framework\TestCase;
use Novel\NovelSpider\Services\Common\RedisConnService;

class TestConn extends TestCase
{
    public function testgetRedisInstance()
    {
        $redisInfo = RedisConnService::getRedisInstance();
        $this->assertTrue($redisInfo['status'] == 1);
    }
}
