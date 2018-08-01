<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/15
 * Time: 上午8:58
 */

for ($i=0;$i<3;$i++) {
    go(function()use($i){
        $swoole_mysql = new Swoole\Coroutine\MySQL();
        $swoole_mysql->connect([
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => '123456',
            'database' => 'node',
        ]);
        $res = $swoole_mysql->query('select * from node_article limit '.($i*2).', 2');
        var_dump($res);
        echo $i,PHP_EOL;
        co::sleep(2);
        echo co::getHostByName('laravel.suhanyu.top');
    });
}


