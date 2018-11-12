<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/7
 * Time: 下午2:32
 */

namespace Libs\Core\Route;

use Novel\Controllers;

class Router
{
    public function __construct()
    {

    }

    public static function match($type=['get'],$path='',$method=''):array
    {
        $methodArr = explode('@', $method);
        if (count($methodArr)<2) {
            throw new \Exception("路由{$method}配置错误！");
        }
        $controllerName = $methodArr[0];
        $methodName = $methodArr[1];
        $path = str_replace('/','\\',$path);
        $controllerName = "Novel\\Controllers\\{$path}\\{$controllerName}";
        try {
            $result = call_user_func([(new $controllerName), $methodName]);
        } catch (\Exception $e) {
            $result = [
                'status' => $e->getCode(),
                'msg'    => $e->getMessage(),
            ];
        }

        return $result;
    }
}
