<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/22
 * Time: 下午2:51
 */

/**
 * @desc 用php实现一个http服务器
 */
set_time_limit(3);
$count = 0;
$socketUrl = 'tcp://0.0.0.0:3002';
$socket = stream_socket_server($socketUrl, $errorNo, $errorStr);
stream_set_blocking($socket, 0);
if (!$socket) {
    throw new ErrorException('create socket failed...');
}
$conn = stream_socket_accept($socket);
$requestData = fread($conn, 1024);
var_dump($requestData);exit('下午4:27');
// 解析请求数据
// todo
$date = date('Y-m-d H:i:s');
$responseStr = <<<EOS
HTTP/1.1 200 OK
Server: zhaoyouwang...;
Content-Type:text/html;charset=utf8

Content...."{$date}"
EOS;
fwrite($conn, $responseStr);
fclose($conn);
fclose($socket);

