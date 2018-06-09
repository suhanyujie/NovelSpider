<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/9
 * Time: 下午12:43
 */
//select的系统调用 https://blog.ti-node.com/blog/6389426571769282560
$configArr = [
    'host' => '0.0.0.0',
    'port' => 8001,
];
$listenSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($listenSocket, $configArr['host'], $configArr['port']);
socket_listen($listenSocket);
//将监听socket放入到read fd set中去，因为select也要监听listen_socket上发生事件
$clients = [$listenSocket];
$write = [];
$exp = [];

while (1) {
    $read = $clients;
    //第4个参数，如果写成null，那么表示select会阻塞一直到监听发生变化
    if (socket_select($read, $write, $exp, null)) {
        if (in_array($listenSocket, $read)) {
            //接收到一个客户端 则将其存放到clients数组中
            $clientSocket = socket_accept($listenSocket);
            $clients[] = $clientSocket;
            //将发消息的client从数组中去除
            $key = array_search($listenSocket, $read);
            unset($read[$key]);
        } else {
            echo 'client被关闭...'.date('YmdHis').PHP_EOL;
            sleep(1);
            continue;
        }
        //查看去除了可读的客户端后 是否还有client_socket
        if (count($read) > 0) {
            $msg = 'hello world...';
            foreach ($read as $socketItem) {
                //从可读中去除数据内容
                $content = socket_read($socketItem, 2048);
                //将内容分发给其他的客户端
                foreach ($clients as $clientSocket) {
                    if ($clientSocket != $listenSocket && $clientSocket != $socketItem) {
                        socket_write($clientSocket, $content, strlen($content));
                    }
                }
            }
        }
    } else {
        sleep(1);
        continue;
    }
}
