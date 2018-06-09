<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/9
 * Time: 下午3:29
 */
//php定时器 https://blog.ti-node.com/blog/6396317917192912897
//安装alarm信号处理器
$count = 0;
pcntl_signal(SIGALRM, function()use(&$count){
    echo $count.'tick'.PHP_EOL;
    $count++;
    if ($count>3) {
        exit($count.'退出...'.PHP_EOL);
    }
});
$tick = 1;
while(1){
    //每$tick秒发送一个alarm信号
    pcntl_alarm($tick);
    pcntl_signal_dispatch();
    sleep($tick);
}


