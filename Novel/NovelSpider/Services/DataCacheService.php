<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/5/16
 * Time: 18:11
 */

namespace Novel\NovelSpider\Services;


class DataCacheService
{
    protected static $cacheData = [];

    public static function get($key='', $default='')
    {
        return self::$cacheData[$key] ?? $default;
    }

    public static function set($key='', $value='')
    {
        self::$cacheData[$key] = $value;
    }
}
