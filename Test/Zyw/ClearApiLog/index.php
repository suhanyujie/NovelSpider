<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/15
 * Time: 下午2:04
 */

/**
 * 按行读取文件
 * @param string $filename
 * @return array
 * @throws Exception
 * @other http://www.liqingbo.cn/blog-1292.html
 */
function readFilePartByLine($paramArr = [])
{
    $options = [
        'filename'    => '',//
        'offsetLines' => 0,
        'length'      => 2,
    ];
    is_array($paramArr) && $options = array_merge($options, $paramArr);
    extract($options);
    if (!$paramArr['filename']) {
        throw new \Exception('参数不合法$filename！');
    }
    $fh = fopen($filename, 'r');
    $times = 0;
    $contentArr = [];
    while (!feof($fh)) {
        // 超过指定的行数，则跳出
        if ($times > $length) {
            break;
        }
        //如果没到指定行数，则继续到下一行
        $line = fgets($fh);
        if ($times < $offsetLines) {
            continue;
        }
        //如果内容为换行，则跳过
        if (!preg_match('@[^\s]+\s{0,}@', $line)) {
            continue;
        }
        $contentArr[] = $line;
        $times++;
    }
    fclose($fh);

    return $contentArr;
}

$filename = "/Users/suhanyu/360云盘/我的电脑/D盘/2016zol/mywork/所有项目/201806/pj3-api无用日志清理/other_api2.txt";
$contentArr = readFilePartByLine([
    'filename'    => $filename,
    'offsetLines' => 0,
    'length'      => 100,
]);

var_dump($contentArr);exit('下午3:04');




