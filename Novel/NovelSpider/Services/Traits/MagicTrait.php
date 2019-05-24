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
    protected static $instance;

    protected $magicTraitData = [];

    public function getIns($param=[])
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __get($key = '')
    {
        return $this->magicTraitData[$key] ?? '';
    }

    public function __set($key='', $value=[])
    {
        $this->magicTraitData[$key] = $value;
    }

    public function __toString()
    {
        var_export($this->magicTraitData);
    }

    public function __invoke()
    {
        throw new \Exception("can not be callabled", -234);
    }

    // 自 PHP 5.1.0 起当调用 var_export() 导出类时，此静态 方法会被调用。
    public static function __set_state(array $properties)
    {

    }

    public function __clone()
    {

    }

    public function __isset($key='')
    {
        return isset($this->magicTraitData[$key]) ?? false;
    }

    public function __unset($key=''):void
    {
        unset($this->magicTraitData[$key]);
    }

    // 析构方法
    public function __destruct()
    {
        $this->magicTraitData = null;
    }
}
