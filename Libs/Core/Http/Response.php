<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/4/5
 * Time: 14:56
 */

namespace Libs\Core\Http;

use Workerman\Protocols\Http;

class Response
{
    public static function setAccessAllowHeader()
    {
        Http::header('Access-Control-Allow-Method:PUT,POST,GET,DELETE,OPTIONS');
        Http::header('Content-type:application/json;charset=utf-8');
        Http::header('Access-Control-Allow-Origin:*');
        Http::header('Access-Control-Allow-Credentials:true');
        Http::header("Access-Control-Allow-Headers:Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
    }
}
