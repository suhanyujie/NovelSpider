<?php
/**
 * 数据库链接的配置文件
 */
namespace Config;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * 数据库相关的操作
 * Class Db
 * @package Config
 */
class Db
{
    // 单例时的数据库链接对象存储
    public static $singleInstance = null;

    // 初始化数据库链接
    public static function init()
    {
        $envConf = EnvConfig::getAll();
        $dbConfig = $envConf['start_list_db'] ?? [];
        //数据库加载配置文件
        $database = [
            'driver'    => 'mysql',
            'host'      => $dbConfig['DB_HOST'],
            'database'  => $dbConfig['DB_DATABASE'],
            'username'  => $dbConfig['DB_USER'],
            'password'  => $dbConfig['DB_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ];
        $capsule = new Capsule;
        $capsule->addConnection($database);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}


