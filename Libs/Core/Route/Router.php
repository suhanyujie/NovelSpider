<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/7
 * Time: 下午2:32
 */

namespace Libs\Core\Route;


class Router
{
    public function __construct()
    {

    }

    public static function match($type=['get'],$path='',$method=''):bool
    {
        $methodArr = explode('@', $method);
        if (count($methodArr)<2) {
            throw new \Exception("路由{$method}配置错误！");
        }
        $controllerName = $methodArr[0];
        $methodName = $methodArr[1];

    }
}
