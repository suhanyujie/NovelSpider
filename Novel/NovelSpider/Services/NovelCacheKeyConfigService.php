<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/4/27
 * Time: 12:46
 */

namespace Novel\NovelSpider\Services;


class NovelCacheKeyConfigService
{
    /**
     * 小说列表的key
     * 使用时，需在字符串后加上对应的小说 id，例如：`novel-list-key:1`
     */
    const NOVEL_LIST_KEY = "novel-list-key";

    /**
     * 小说状态变更锁的键名称
     * 使用时，需在字符串后加上对应的小说 id，例如：`novel-status-lock-key:1`
     */
    const NOVEL_STATUS_LOCK_KEY = "novel-status-lock-key";
}
