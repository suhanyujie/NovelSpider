<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/4/22
 * Time: 下午2:56
 */
declare(ticks=1);
$forkArr = [];
$pid = posix_getpid();
echo 'master pid:', $pid, PHP_EOL;
pcntl_signal(SIGINT, 'signalHandler', false);

$pid = pcntl_fork();
if ($pid == -1) {
    die('子进程创建失败');
} else {
    if ($pid == 0) {
        // 以下代码在子进程中运行
        echo "这是子进程，pid：" . posix_getpid() . "\n";
        $i = 13;
        while ($i) {
            pcntl_signal_dispatch();
            sleep(2);
            $i--;
        }
        echo 'child process exit',PHP_EOL;
        exit();
    } else {
        $forkArr[] = $pid;
        // 以下代码在父进程中运行
        echo "这是父进程，pid：" . getmypid() . "\n";
        $status = 0;
        sleep(2);
        echo '可以kill了',PHP_EOL;
        // $pid = pcntl_wait($status, WUNTRACED); // 堵塞直至获取子进
        //程退出或中断信号或调用一个信号处理器，或者没有子进程时返回错误
    }
}

sleep(2);
while(1){
    var_dump($forkArr);
    foreach ($forkArr as $pid) {
        echo "process exit by master.. pid：{$pid}\n" ;
        posix_kill($pid, SIGINT);
    }
    sleep(2);
}

/**
 * @desc 安装信号处理
 * @return void
 */
function signalHandler($sigNo)
{
    global $forkArr;
    switch ($sigNo) {
        case SIGKILL:
        case SIGINT:
            if ($forkArr) {
                foreach ($forkArr as $pid) {
                    echo "process exit by signalHandler.. pid：{$pid}\n" ;
                    posix_kill($pid, SIGINT);
                }
            }
            $pid = posix_getpid();
            file_put_contents('/www/www/Test/HttpServer/test.log',$pid.' exit by signalHandler'.PHP_EOL, FILE_APPEND);
            exit(0);
        case SIGUSR1:
            echo '处理用户自定义信号', PHP_EOL;
            break;
    }
}