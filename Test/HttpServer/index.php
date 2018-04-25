<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/22
 * Time: 上午9:47
 */
/**
 * @desc 用php实现一个http服务器
 */
set_time_limit(3);
$socketUrl = 'tcp://0.0.0.0:3002';
$count = 0;
$socket = stream_socket_server($socketUrl, $errorNo, $errorStr);
stream_set_blocking($socket, 1);
if (!$socket) {
    throw new ErrorException('create socket failed...');
} else {
    while ($conn = stream_socket_accept($socket, 300)) {
        $requestData = fread($conn, 1024);
        var_dump($requestData);
        // 解析请求数据
        // todo
        $date = date('Y-m-d H:i:s');
        $responseStr = <<<EOS
HTTP/1.1 200 OK
Server: zhaoyouwang...;
Content-Type:text/html;charset=utf8;
Server2:zhaoyou;

Content...."{$date}"
EOS;
        echo 'have a connection...',$count.' connectino had served...', PHP_EOL;
        // todo
        $s = mt_rand(2,5);
        sleep($s);
        fwrite($conn, $responseStr);
        fclose($conn);
        echo 'connection ',$count,' has complete..',$s, PHP_EOL;
        $count++;
    }
    fclose($socket);
}
