<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/5/24
 * Time: 上午8:29
 */
/**
 * @desc 协程
 */
$contentArr = [];
$str = '123123';
$startTime = microtime(true);

/*
go(function ()use(&$contentArr) {
    $str = file_get_contents('https://www.qu.la/book/746/10575034.html');
    $contentArr[] = $str;
    echo "hello";
});

go(function () use(&$contentArr){
    $str = file_get_contents('https://www.qu.la/book/746/10575052.html');
    echo "world";
    $contentArr[] = $str;
    var_dump($contentArr);
});*/
$str = file_get_contents('https://www.qu.la/book/746/10575034.html');
$contentArr[] = $str;
$str = file_get_contents('https://www.qu.la/book/746/10575052.html');
$contentArr[] = $str;
var_dump($contentArr);


$endTime = microtime(true);
echo $spend = round($endTime-$startTime, 4);


function test(){
    echo 'test func1';
}
