<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/10/26
 * Time: 下午10:10
 */

namespace Libs\Helper;

/**
 * @desc 公共的帮助函数
 * Class PublicFunction
 * @package Libs\Helper
 */
class PublicFunction
{
    /**
     * @desc 获取当前项目 storage文件夹的绝对路径
     */
    public static function storage_path()
    {
        $path = dirname(dirname(__DIR__)).'/storage';
        return $path;
    }

    // 获取网页内容，并将 gbk 转换为 utf-8
    public static function getHtmlContents($url = ''): string
    {
        $opts = [
            'http' => [
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                    "Cookie: foo=bar\r\n" .
                    "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Host: www.biqigewx.com\r\n" .
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36\r\n" .
                    "Referer: http://www.biqigewx.com/5_5270/\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $html = file_get_contents($url, false, $context);
        return iconv('GBK', 'UTF-8' . '//IGNORE', $html);
    }
}
