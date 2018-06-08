<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/2
 * Time: 上午11:34
 */
// 设置umask为0，这样，当前进程创建的文件权限则为777
echo getcwd() . "\n";
umask( 0 );
$pid = pcntl_fork();
if( $pid < 0 ){
    exit('fork error.');
} else if( $pid > 0 ) {
    // 主进程退出
    exit();
}
// 子进程继续执行
// 最关键的一步来了，执行setsid函数！
if( !posix_setsid() ){
    exit('setsid error.');
}
// 理论上一次fork就可以了
// 但是，二次fork，这里的历史渊源是这样的：在基于system V的系统中，通过再次fork，父进程退出，子进程继续，保证形成的daemon进程绝对不会成为会话首进程，不会拥有控制终端。
$pid = pcntl_fork();
if( $pid  < 0 ){
    exit('fork error');
} else if( $pid > 0 ) {
    // 主进程退出
    exit;
}
// 子进程继续执行
// 啦啦啦，啦啦啦，啦啦啦，已经变成daemon啦，开心
//cli_set_process_title('testtesttest');
// 一般服务器软件都有写配置项，比如以debug模式运行还是以daemon模式运行。如果以debug模式运行，那么标准输出和错误输出大多数都是直接输出到当前终端上，如果是daemon形式运行，那么错误输出和标准输出可能会被分别输出到两个不同的配置文件中去
// 连工作目录都是一个配置项目，通过php函数chdir可以修改当前工作目录
$dir = '/';
chdir( $dir );
echo getcwd() . "\n";
// todo45
sleep(15);

