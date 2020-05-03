<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-05-03
 * Time: 10:15
 */

namespace Novel\Consoles\Exports;

use Novel\Consoles\BaseConsole;
use Novel\Consoles\CliInterface;

/**
 * 将文件上传到云端短暂保存 保存 14 天
 * Class UploadToCloud
 * @package Novel\Consoles\Exports
 */
class UploadToCloud extends BaseConsole implements CliInterface
{
    protected $data = [];

    protected static $staticData = [];

    protected $signature = 'novel:uploadToCloud';

    public function handle()
    {
        $file = 'dist/test.txt';
//        $result = CurlRequest::post([
//            'url'    => 'https://bitsend.jp/jqu/',
//            'method' => 'POST',
//            'headers' => [
//                'Content-Type'     => 'multipart/form-data',
//                'X-Requested-With' => 'XMLHttpRequest',
//                'Referer'          => 'https://bitsend.jp/?setLang=en',
//                'User-Agent'       => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',
//                'Cookie'           => 'PHPSESSID=5565fd97bda5c895a2b269514a26899d',
//            ],
//            'multipart'=>[
//                [
//                    'name'     => 'files[]',
//                    'filename' => $file,
//                    'contents' => fopen($file, 'r'),
//                    'headers'  => [
//                        'Content-Type' => 'multipart/form-data',
//                    ],
//                ]
//            ],
//        ]);
        $baseUrl = 'https://bitsend.jp';
        $result = $this->formPost([
            'url'    => "$baseUrl/jqu/",
            'method' => 'POST',
            'headers' => [
                'Content-Type'     => 'multipart/form-data',
                'X-Requested-With' => 'XMLHttpRequest',
                'Referer'          => 'https://bitsend.jp/?setLang=en',
                'User-Agent'       => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36',
                'Cookie'           => 'PHPSESSID=5565fd97bda5c895a2b269514a26899d',
            ],
            'post_data'=>[
                'files[]'=> new \CURLFILE('/Users/suhanyu/Documents/占位图/image.png'),
                'u_key' => '0f9d894330c628a20a71a57f2cf9542f'
            ],
        ]);
        $resArr = json_decode($result, true);
        $firstFileRes = $resArr['files'][0] ?? [];
        if (empty($firstFileRes)) {
            throw new \Exception("上传响应异常", -1);
        }
        /*
         {
                "name": "7f78a20693064f1c29d1d47078424602.png",
                "size": 1905,
                "type": "application/octet-stream",
                "niceSize": "1.86 KB",
                "realName": "image.png",
                "fileKey": "ecf6b5bf1ad859e19170b12a95b8555d",
                "delFileKey": "17f3575444d30bbd7463ccb6d53e248d",
                "delete_url": "https://bitsend.jp/jqu/?file=7f78a20693064f1c29d1d47078424602.png",
                "delete_type": "DELETE"
        }
        */
        $result = self::getUploadedInfo($firstFileRes);
        echo "下载链接：".$result['downloadLink'] ?? '' . PHP_EOL;

        // return $result;
    }

    // 获取上传后的信息
    public static function getUploadedInfo($oneResult = [])
    {
        if (empty($oneResult)) throw  new \Exception("获取上传后的信息入参为空", -1);
        // 原始信息
        self::$staticData['originResponse'] = $oneResult;
        // 下载链接
        $downloadLink = "https://bitsend.jp/download/{$oneResult['fileKey']}.html";
        // 删除链接
        $delLink = "https://bitsend.jp/delete/{$oneResult['delFileKey']}.html";
        // 二维码地址
        // https://chart.googleapis.com/chart?chs=150&cht=qr&chl=https://bitsend.jp/download/e07d24defd68a5737deb788d055f884f.html&choe=UTF-8&chld=|0
        $qrCodeUrl = "https://chart.googleapis.com/chart?chs=150&cht=qr&chl={$downloadLink}&choe=UTF-8&chld=|0";
        $returnArr = [
            'downloadLink' => $downloadLink,
            'delLink'      => $delLink,
            'qrCodeUrl'    => $qrCodeUrl,
            'originData'   => $oneResult,
        ];

        return $returnArr;
    }

    public function formPost($params = [])
    {
        $options = [
            'url'       => '',
            'method'    => 'POST',
            'headers'   => [],// ['Content-type'=>'', 'Referer'=>'',]
            'post_data' => [],
        ];
        $options = array_merge($options, $params);
        if (!empty($options['headers'])) {
            $headers = [];
            foreach ($options['headers'] as $name=>$value) {
                $headers[] = "{$name}: {$value}";
            }
            $options['headers'] = $headers;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $options['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $options['method'],
            CURLOPT_POSTFIELDS => $options['post_data'],
            CURLOPT_HTTPHEADER => $options['headers'],
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
