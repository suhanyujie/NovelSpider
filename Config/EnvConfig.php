<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-05-02
 * Time: 11:40
 */

namespace Config;

use function FastRoute\TestFixtures\empty_options_cached;

class EnvConfig
{
    public static $staticData = [];

    public static $envConfig = [];

    public static function init()
    {
        if (!defined('ROOT')) {
            define('ROOT', realpath(__DIR__ . '/../../'));
        }
        //解析配置文件
        $envConfig = parse_ini_file(ROOT . "/.env", true);
        self::$envConfig = $envConfig;
    }

    /**
     * @desc 获取所有的 env 配置数据
     */
    public static function getAll()
    {
        if (empty(self::$envConfig)) {
            self::init();
        }

        return self::$envConfig;
    }
}
