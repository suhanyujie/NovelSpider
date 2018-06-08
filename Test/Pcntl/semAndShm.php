<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/3
 * Time: 上午11:41
 */
//信号量和共享内存
//https://blog.ti-node.com/blog/6379989346195341312

//信号量
$semKey = ftok(__FILE__, 'b');
$semId = sem_get($semKey);
//共享内存
$shmKey = ftok(__FILE__, 'm');
$shmId = shm_attach($shmKey, 1024, 0666);
const SHM_VAR = 1;
$childPid = [];
//process number
$processNum = 2;
for ($i = 1; $i <= $processNum; $i++) {
    $pid = pcntl_fork();
    if ($pid < 0) {
        exit('fork error!');
    } else {
        if (0 == $pid) {
            //子进程获得信号量（相当于锁）
            $getLockResult = sem_acquire($semId);
            if (shm_has_var($shmId, SHM_VAR)) {
                $counter = shm_get_var($shmId, SHM_VAR);
                $counter += 1;
            } else {
                $counter = 1;
            }
            $updateDataResult = shm_put_var($shmId, SHM_VAR, $counter);
            var_dump($updateDataResult);
            $curPid = posix_getpid();
            echo '$curPid:' . $curPid . '->' . $counter . PHP_EOL;
            //释放锁 一定记得释放不然会死锁
            sem_release($semId);
            exit('child exit' . PHP_EOL);
        } elseif ($pid > 0) {
            $childPid[] = $pid;
        }
    }
}
//派遣信号
while (count($childPid)>0) {
    foreach ($childPid as $pidItem) {
        $waitResult = pcntl_waitpid($pidItem, $status, WNOHANG);
        if ($waitResult < 0 || $waitResult > 0) {
            unset($childPid[$pidItem]);
        }
        //为0 时表示没有可用子进程
        //文档：如果提供了 WNOHANG作为option（wait3可用的系统）并且没有可用子进程时返回0
        if ($waitResult === 0) {
            break 2;
        }
    }
    sleep(1);
//    var_dump($childPid);
}
sleep(2);
echo '最终结果：' . shm_get_var($shmId, SHM_VAR) . PHP_EOL;
//删除共享内存数据 先remove 再detach
shm_remove($shmId);
shm_detach($shmId);

