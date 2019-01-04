<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/4
 * Time: 上午9:55
 */
/**
 * 自定义函数 辅助函数声明
 */
if (!function_exists('app'))
{
    function app($abstract=null,array $parameters=[])
    {
        if (is_null($abstract)) {
            return Illuminate\Container\Container::getInstance();
        }

        return \Illuminate\Container\Container::getInstance()->make($abstract, $parameters);
    }
}
