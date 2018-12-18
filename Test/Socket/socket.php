<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/12/18
 * Time: 下午9:16
 */

$socket = socket_create(AF_INET, SOCK_STREAM,SOL_TCP);
socket_bind($socket,'0.0.0.0',3001);
if (!socket_listen($socket)) {

} else {

}

var_dump(socket_last_error($socket),socket_strerror(0));exit(PHP_EOL.'下午9:17'.PHP_EOL);

