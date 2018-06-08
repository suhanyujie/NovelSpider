<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/5
 * Time: 上午8:14
 */
$key = ftok(__DIR__, 'a');
$queue = msg_get_queue($key, 0666);
//var_dump(msg_stat_queue($queue));exit('上午8:15');
$pid = pcntl_fork();
if ($pid>0){
    //父进程
    msg_receive($queue, 0, $msgType, 1024, $message);
    echo $message,PHP_EOL;
    //clear the message queue
    msg_remove_queue($queue);
    pcntl_wait($status);
}elseif($pid==0){
    //子进程，向消息队列写入数据
    msg_send($queue, 1, 'hello suhanyu');
    exit('child exit..'.PHP_EOL);
}else{
    exit('fork error!');
}

