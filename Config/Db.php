<?php
/**
 * 数据库链接的配置文件
 */
namespace Config;

class Db
{

    /**
     * 数据库的一个实例配置，则使用时像下面这样使用
     * $user_array = Db::instance('db1')->select('name,age')->from('users')->where('age>12')->query();
     * 等价于
     * $user_array = Db::instance('db1')->query('SELECT `name`,`age` FROM `users` WHERE `age`>12');
     * @var array
     */
    public static $db1 = array(
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'user'    => 'root',
        'password' => '123456',
        'dbname'  => 'bbs_test',
        'charset'    => 'utf8',
    );



}


