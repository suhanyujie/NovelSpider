<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/9
 * Time: 下午10:51
 */

namespace Libs\Core\Store;

use Illuminate\Container\Container;
use League\Route\Router;

class PrivateStorage
{
    /**
     * @var Router
     */
    public static $router = null;

    /**
     * @var Container
     */
    public static $container = null;
}
