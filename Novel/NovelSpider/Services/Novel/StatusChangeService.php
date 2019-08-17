<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/8/17
 * Time: 13:39
 */

namespace Novel\NovelSpider\Services\Novel;

use Novel\NovelSpider\Models\NovelMainModel;
use Novel\NovelSpider\Services\Common\RedisConnService;
use Novel\NovelSpider\Services\NovelCacheKeyConfigService;

/**
 * 小说“状态”变更
 * Class StatusChangeService
 * @package Novel\NovelSpider\Services\Novel
 */
class StatusChangeService
{
    const CHAPTER_LIST_COMPLETE = 1;
    const CHAPTER_LIST_WAIT = 3;
    const CHAPTER_DETAIL_COLLECTING = 4;
    const CHAPTER_DETAIL_COLLECTED = 5;

    protected static $data = [];

    /**
     * 设置状态为：该小说章节等待采集
     */
    public static function setChapterListWait($id = 0)
    {
        self::setStatus($id, self::CHAPTER_LIST_WAIT);
    }

    /**
     * 设置状态为：该小说列表采集完成
     */
    public static function setChapterListComplete($id = 0)
    {
        self::setStatus($id, self::CHAPTER_LIST_COMPLETE);
    }

    /**
     * 设置状态为：该小说所有章节详情采集中
     */
    public static function setStatusChapterDetailCollecting($id = 0)
    {
        self::setStatus($id, self::CHAPTER_DETAIL_COLLECTING);
    }

    /**
     * 设置状态为：该小说所有章节详情采集完成
     */
    public static function setStatusChapterDetailCollected($id = 0)
    {
        self::setStatus($id, self::CHAPTER_DETAIL_COLLECTED);
    }

    /**
     * 更新小说状态（novel_main 表中的状态）
     * @param int $id novel_main 表的主键，小说 id
     * @param int $status
     * @return array
     * @throws \Exception
     */
    public static function setStatus($id = 0, $status = 1)
    {
        if (empty($id)) {
            return ['status' => 0, 'msg' => '参数 id 为空！'];
        }
        if (!isset(self::$data['NovelMainModel'])) {
            self::getNovelMainModel();
        }
        // 获取 redis 锁；如果需要等待锁，则做多等待 10s
        $maxTryTimes = 10;
        do {
            $lockIsOk = self::getUpdateLock($id);
            if ($lockIsOk) {
                break;
            }
            sleep(1);
        } while ($maxTryTimes--);
        if ($lockIsOk === false) {
            return ['status' => 0, 'msg' => '获取更新锁失败！'];
        }
        $model = self::$data['NovelMainModel'];
        $result = $model->where('id', $id)->update([
            'novel_status' => $status,
            'update_time'  => date('Y-m-d H:i:s'),
        ]);
        self::unlockUpdateLock($id);
        if ($result) {
            return ['status' => 1, 'msg' => '状态更新成功！'];
        } else {
            return ['status' => 0, 'msg' => '状态更新失败！'];
        }
    }

    /**
     * 获取更新状态的锁
     * @param int $id novel_main 表的主键，小说 id
     * @return bool
     * @throws \Exception
     */
    protected static function getUpdateLock($id = 0)
    {
        global $redis;
        $lockKey = NovelCacheKeyConfigService::NOVEL_STATUS_LOCK_KEY . "{$id}";
        if (empty($redis)) {
            $redisInfo = RedisConnService::getRedisInstance();
            if ($redisInfo['status'] != 1) {
                throw new \Exception("获取 redis 示例失败！[{$redisInfo['msg']}]");
            }
            $redisObj = $redisInfo['data'];
        } else {
            $redisObj = $redis;
        }
        $lockValue = $redisObj->get($lockKey);
        if (empty($lockValue)) {
            // 持有锁的时间最多 60 s
            $redisObj->setex($lockKey, 60, 1);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 释放锁
     * @param int $id
     * @return int
     * @throws \Exception
     */
    protected static function unlockUpdateLock($id = 0)
    {
        global $redis;
        $lockKey = NovelCacheKeyConfigService::NOVEL_STATUS_LOCK_KEY . "{$id}";
        if (empty($redis)) {
            $redisInfo = RedisConnService::getRedisInstance();
            if ($redisInfo['status'] != 1) {
                throw new \Exception("获取 redis 示例失败！[{$redisInfo['msg']}]");
            }
            $redisObj = $redisInfo['data'];
        } else {
            $redisObj = $redis;
        }
        return $redisObj->setex($lockKey, 60, 1);
    }

    /**
     * 获取 model 实例
     */
    public static function getNovelMainModel()
    {
        $model = new NovelMainModel();
        self::$data['NovelMainModel'] = $model;
    }
}
