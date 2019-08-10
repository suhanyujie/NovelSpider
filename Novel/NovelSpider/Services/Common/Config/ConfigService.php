<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/8/10
 * Time: 18:07
 */

namespace Novel\NovelSpider\Services\Common\Config;


class ConfigService
{
    static $envConf = [];

    static public function loadConfig()
    {
        if (empty(self::$envConf)) {
            $rootPath = realpath('./');
            $envConfig = parse_ini_file($rootPath . "/.env", true);
            self::$envConf = $envConfig;
        }
        return self::$envConf;
    }
}
