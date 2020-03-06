<?php
/**
 * 本文主要讲述如何用 PHP 实现 utf-8 编解码
 */

// var_dump($argv);
$param1 = $argv[1] ?? '';
// $upper = strtoupper($param1);
// echo $upper;
// echo "\n";
// die;

for ($i=0; $i < strlen($param1); $i++) {
    $char = $param1[$i];
    // $upperChar = strtoupper($char);
    // PHP 中将字符转为 ASCII
    $charAscii = ord($char);
    $r5ChangeVal = $charAscii >> 5;
    // 字符值转为 二进制 输出
    $binVal = decbin($r5ChangeVal);
    echo $binVal;echo "\n";

    // 移 4 位
    $r4ChangeVal = $charAscii >> 4;
    $binVal = decbin($r4ChangeVal);
    echo $binVal;echo "\n";
    // 移 3 位
    $r3ChangeVal = $charAscii >> 3;
    $binVal = decbin($r3ChangeVal);
    echo $binVal;echo "\n";
    echo "\n";

    // $num1 = $charAscii >> 3;
    // var_dump($charAscii, '---', $num1);
    break;
}


/*
## 缘起
计算机是美国人发明的，在一开始时，计算机内只是内置了他们的语言，并不支持其他国家的语言。
后来人们为了能支持更多的语言文字，发明了 utf-8 编码方式。这样，计算机就能支持各种国家和地区的语言了。

## 编解码
### utf-8 的多字节编码
* 如果一个字节以110开头，则意味着我们需要两个字节
* 如果一个字节以1110开头，则意味着我们需要三个字节
* 如果一个字节以11110开头，则意味着我们需要四个字节
* 如果一个字节以10开头，则表示它是多字节字符序列的延续。

## 参考资料
* PHP 中值的进制转换 https://www.jb51.net/article/112114.htm
* 深入理解php中二进制处理函数pack与unpack函数 https://libisky.com/post/%E6%B7%B1%E5%85%A5%E7%90%86%E8%A7%A3php%E4%B8%AD%E4%BA%8C%E8%BF%9B%E5%88%B6%E5%A4%84%E7%90%86%E5%87%BD%E6%95%B0pack-%E4%B8%8E-unpack%E5%87%BD%E6%95%B0
* Rust为什么会有String和&str？ https://rust.cc/article?id=08bc71ca-7aa1-4fce-93aa-614712430c66

*/
