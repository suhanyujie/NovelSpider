<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/8/10
 * Time: 18:02
 */

namespace Novel\NovelSpider\Services\Common\Mysql;

use Illuminate\Database\Capsule\Manager as Capsule;
use Novel\NovelSpider\Services\Common\Config\ConfigService;

class ConnectorService
{
    static protected $conns = [];


    static public function init()
    {

    }

    public static function getOneConn()
    {
        $envConfig = ConfigService::loadConfig();
        $dbConfig = isset($envConfig['start_list_db']) ? $envConfig['start_list_db'] : [];
        if (empty($dbConfig)) {
            throw new \Exception("数据库信息没有配置！");
        }
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
        self::$conns[] = $capsule;
        return $capsule;
    }
}
