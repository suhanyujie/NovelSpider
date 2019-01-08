<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/4
 * Time: 上午9:52
 */

namespace Libs\Core\Container;

use Illuminate\Container\Container;

class Application extends Container
{
    /**
     * @desc 创建当次请求的app实例
     */
    public function __construct()
    {
        static::setInstance($this);
    }
}
