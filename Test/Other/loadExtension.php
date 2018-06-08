<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/1
 * Time: 上午9:40
 */

$ext = 'xdebug.so';
if (!extension_loaded('xdebug')) {
    $result = dl($ext);
    var_dump($result);exit('上午9:42');
}