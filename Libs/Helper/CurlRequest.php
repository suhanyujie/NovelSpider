<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-05-03
 * Time: 10:23
 */

namespace Libs\Helper;

use Libs\Helper\Traits\HttpRequst;

class CurlRequest
{
    /**@var CurlRequest */
    public static $singleInstance;

    use HttpRequst;

    /**
     * @desc post 请求
     */
    public static function post($params = [])
    {
        $options = [
            'url'    => '',
            'method' => 'post',//
            'header' => [],
            'body'   => '',
        ];
        $options = array_merge($options, $params);
        if (empty(self::$singleInstance)) {
            self::$singleInstance = new self();
        }
        $result = self::$singleInstance->httpRequest($options);
        // todo
    }

    /**
     * @desc post 请求
     */
    public static function get($params = [])
    {
        $options = [
            'url'    => '',
            'method' => 'get',//
            'header' => [

            ],
            'body'   => '',
        ];
        $options = array_merge($options, $params);
        if (empty(self::$singleInstance)) {
            self::$singleInstance = new self();
        }
        $result = self::$singleInstance->httpRequest($options);

        return $result;
    }
}
