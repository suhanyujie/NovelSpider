<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/9/18
 * Time: 下午3:54
 */

namespace Libs\Helper\Traits;

use GuzzleHttp\Client;

trait HttpRequst
{
    protected $httpTraitData = [];

    /**
     * 获取 get 参数
     * @param string $key
     * @return array|mixed|string
     */
    public function get($key = '')
    {
        $getData = !empty($this->data['get'])  ? $this->data['get'] : [];
        if (empty($key)) {
            return $getData;
        }
        return empty($getData[$key]) ? '' : $getData[$key];
    }

    /**
     * 发送 http 请求
     * @param array $paramArr
     * @return array
     */
    public function httpRequest($paramArr=[])
    {
        $options = [
            'url'    => '',
            'method' => 'post',//
            'header' => [],
            'body'   => '',
            'debug'  => '',
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        $httpClient = $this->getClient();
        try {
            $response = $httpClient->request($options['method'], $options['url'], [
                'headers' => $options['header'],
                'body'    => $options['body'],
            ]);
        } catch (\Exception $e) {
            return ['status' => $e->getCode(), 'message' => '请求错误，原因：' . $e->getMessage()];
        }
        $responseBodyStr = (string)$response->getBody();
        if ($options['debug'] == 2) {
            echo $responseBodyStr;exit('--debug--'.PHP_EOL);
        }
        $responseArr = json_decode($responseBodyStr, true);

        return ['status'=>1, 'data'=>$responseArr,];
    }

    /**
     * @desc 获取http客户端
     * @return mixed
     */
    public function getClient()
    {
        if(isset($this->httpTraitData['httpClient']))return $this->httpTraitData['httpClient'];
        $this->httpTraitData['httpClient'] = new Client([
            'base_uri' => '',
            'timeout'  => 30,
        ]);

        return $this->httpTraitData['httpClient'];
    }
}
