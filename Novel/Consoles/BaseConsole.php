<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-05-02
 * Time: 11:32
 */

namespace Novel\Consoles;


class BaseConsole
{
    protected $signature = 'test:example';

    /**
     * @desc 获取对象的 signature 属性
     */
    public function getSignature()
    {
        return $this->signature;
    }
}
