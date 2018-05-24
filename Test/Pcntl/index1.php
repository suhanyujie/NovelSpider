<?php
/**
 * 案例参考  https://blog.ti-node.com/blog/6363989547574886401
 * 文章tag 多进程 写时复制
 *
 */

/*
$pid = pcntl_fork();
if ($pid > 0) {
    var_dump($pid);
    $pid = posix_getpid();
    echo $pid . "我是父亲" . PHP_EOL;
} else if (0 == $pid) {
    echo "我是儿子" . PHP_EOL;
} else {
    echo "fork失败" . PHP_EOL;
}*/

//第二段，说明子进程拥有父进程数据的副本（并且写时复制），而不是共享
// 初始化一个 number变量 数值为1
$number = 1;
$pid = pcntl_fork();
if( $pid > 0 ){
    echo 'parent id:'.posix_getpid().PHP_EOL;
    sleep(1);
} else if( 0 == $pid ) {
    for ($i=0;$i<10;$i++) {
        echo posix_getppid().PHP_EOL;
        sleep(1);
    }
} else {
    echo "fork失败".PHP_EOL;
}