<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/12/18
 * Time: 下午9:16
 */
/**
 * 参考 https://my.oschina.net/cart/blog/206830
 */
$socket = socket_create(AF_INET, SOCK_STREAM,SOL_TCP);
socket_bind($socket,'0.0.0.0',3001);
$ret = socket_listen($socket);
if (!$ret || $ret < 0) {
    var_dump(socket_last_error($socket),socket_strerror(0));exit(PHP_EOL.'下午9:17'.PHP_EOL);
}
while (1) {
    //已连接描述符
    $conn = socket_accept($socket);
    if (!$conn) {
        echo "conn:{$conn}\n";
        exit();
    }
    $msg = "12312312\n";
    socket_write($conn, $msg, strlen($msg));
    var_dump($conn);
    sleep(2);
}



