<?php
/**
 * @desc 信号
 * https://blog.ti-node.com/blog/6375675957193211905
 */
//Mac下不支持函数cli_set_process_title
$pid = pcntl_fork();

if ($pid < 0) {
    exit('fork error!');
} elseif ($pid > 0) {
    $setTitleRes = cli_set_process_title('php father process');
    echo '设定进程title结果：'.$setTitleRes;
    //父进程中 这个是大于0
    pcntl_signal(SIGCHLD, function () use ($pid) {
        echo '这是父进程，并且他的子进程创建成功！' . PHP_EOL;
        //WNOHANG的作用是 如果没有子进程退出立刻返回。
        pcntl_waitpid($pid, $status, WNOHANG);
    });
    while (1) {
        pcntl_signal_dispatch();
        sleep(1);
    }
} elseif ($pid == 0) {
    //子进程中
    cli_set_process_title('php child process');
    sleep(20);
    echo '这是子进程，即将退出！' . PHP_EOL;
    exit();
}



