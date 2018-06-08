<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/3
 * Time: 上午10:57
 */
// 由于*NIX好像并没有（如果有，请告知）可以获取父进程fork出所有的子进程的ID们的功能，所以这个需要我们自己来保存
$child_pid = [];

//安装信号处理器
pcntl_signal(SIGCHLD, function () use (&$child_pid) {
    $childNum = count($child_pid);
    if ($childNum > 0) {
        foreach ($child_pid as $k => $item) {
            $waitResult = pcntl_waitpid($item, $status, WNOHANG);
            if ($waitResult == $item || -1 == $item) {
                unset($child_pid[$item]);
            }
        }
    }
});
$processNum = 5;
for ($i = 0; $i < $processNum; $i++) {
    $_pid = pcntl_fork();
    if ($_pid > 0) {
        //父进程
        $child_pid[$i] = $_pid;
    }elseif($_pid==0){
        //子进程
        sleep(30);
        //子进程执行完任务 退出
        exit('task process '.$i.' done~');
    }else{
        exit('fork error!');
    }
}
while (1) {
    pcntl_signal_dispatch();
    sleep(1);
}
