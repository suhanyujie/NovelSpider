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
    public static function getHtmlContents1($url = ''): string
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

    // 获取网页内容，并将 gbk 转换为 utf-8
    public static function getHtmlContents($url = ''): string
    {
        $html = CurlRequest::get([
            'url' => $url,
            'header' => [
                'accept'=>'*/*',
                'sec-ch-ua'=>'"Google Chrome";v="93", " Not;A Brand";v="99", "Chromium";v="93"',
                'sec-fetch-site'=>'same-origin',
                'sec-ch-ua-platform'=>'"Windows"',
                'pragma'=>'no-cache',
                ':path'=>'/modules/article/articlevisit.php?id=30825',
                'Cookie' => 'Hm_lvt_561c4a351d61fb1471090bb1fd9dac46=1632563202; __gads=ID=50acede275dcc134-22cd9beadfcb00f6:T=1632563201:RT=1632563201:S=ALNI_MaNvWduPbUBMyW4Z1kywBH55M8yrw; jieqiVisitId=article_articleviews%3D30825%7C113675; Hm_lpvt_561c4a351d61fb1471090bb1fd9dac46=1632578433',
                'Content-Type' => 'text/html',
                'Host' => 'www.biqigewx.com',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/93.0.4577.82 Safari/537.36',
                'Referer' => 'https://www.bbiquge.net/book_30825/',
            ],
            'returnType' => 'origin',
            'verify'=>true,
            'debug'=>true,
            'read_timeout'=>10,
//            'proxy' => [
//                'http'  => 'tcp://127.0.0.1:10808',
//                'https'  => 'tcp://127.0.0.1:10808',
//            ],
        ]);
        return iconv('GBK', 'UTF-8' . '//IGNORE', $html);
    }
}
