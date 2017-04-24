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

class SmallFunc extends TestCase
{

    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }


}//  end of class