<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/5/23
 * Time: 18:57
 */

namespace Novel\NovelSpider\Services\Traits;


trait MagicTrait
{
    protected $magicTraitData = [];

    public function __get($key = '')
    {
        return $this->magicTraitData[$key] ?? '';
    }

    public function __set($key='', $value=[])
    {
        $this->magicTraitData[$key] = $value;
    }
}
