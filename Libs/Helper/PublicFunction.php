<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/10/26
 * Time: 下午10:10
 */

namespace Libs\Helper;


/**
 * @desc 公共的帮助函数
 * Class PublicFunction
 * @package Libs\Helper
 */
class PublicFunction
{
    /**
     * @desc 获取当前项目 storage文件夹的绝对路径
     */
    public static function storage_path()
    {
        $path = dirname(dirname(__DIR__)).'/storage';
        return $path;
    }
}