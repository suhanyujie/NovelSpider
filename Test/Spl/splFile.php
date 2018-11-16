<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/15
 * Time: 下午1:07
 */

$file = new SplFileObject(__DIR__.'/../Spider/SmallFuncTest.php');
//获取文件行
var_dump($file->fgets());exit(PHP_EOL.'下午1:10'.PHP_EOL);



