<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/8/17
 * Time: 14:28
 */

namespace Novel\NovelSpider\Services\Common;

use Predis;

/**
 * redis 相关的方法
 *
 * Class RedisConnService
 * @package Novel\NovelSpider\Services\Common
 */
class RedisConnService
{
    protected static $redisIns = null;

    public static function getRedisInstance()
    {
        if (!empty(self::$redisIns)) {
            return ['status'=>1, 'data'=>self::$redisIns, ];
        }
        try {
            $redisObj = new Predis\Client();
        } catch (\Exception $e) {
            return ['status'=>0, 'msg'=>$e->getMessage()];
        }
        self::$redisIns = $redisObj;
        return ['status'=>1, 'data'=>$redisObj, ];
    }
}
