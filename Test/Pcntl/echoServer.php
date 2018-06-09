<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/8
 * Time: 上午8:27
 */
/**
 * @desc 创建一个多进程的http服务器
 * @url https://blog.ti-node.com/blog/6382424397004668928
 * @other 使用命令释放端口 lsof -i:9999
 *      查看是否有未释放的进程资源 lsof -n | grep deleted
 */
$config = [
    'host'             => '0.0.0.0',
    'port'             => 9999,
    'workerProcessNum' => 3,
];
//创建一个tcp socket
$listenSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//绑定端口
socket_bind($listenSocket, $config['host'], $config['port']);
//开始监听socket
socket_listen($listenSocket);
//创建指定数量的子进程 用于处理请求
for ($i = 0; $i < $config['workerProcessNum']; $i++) {
    $pid = pcntl_fork();
    if ($pid == 0) {
        $dealedConnectionNum = 0;
        while (1) {
            $connectionSocket = socket_accept($listenSocket);
            $msg = "hello world..\r\n";
            socket_write($connectionSocket, $msg, strlen($msg));
            socket_close($connectionSocket);
            $dealedConnectionNum++;
            $curPid = posix_getpid();
            echo '进程'.$curPid.' 处理的连接数：'.$dealedConnectionNum.PHP_EOL;
        }
    }
}
//主进程不退出
//真正该做的应该是收集子进程pid，监控各个子进程的状态等等
while (1) {
    sleep(2);
}
socket_close($listenSocket);
