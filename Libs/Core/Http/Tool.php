<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/10
 * Time: 上午9:01
 */

namespace Libs\Core\Http;

use Zend\Diactoros\ServerRequest;
use function GuzzleHttp\Psr7\_parse_message;
use function GuzzleHttp\Psr7\_parse_request_uri;

class Tool
{
    /**
     * Parses a request message string into a request object.
     *
     * @param string $message Request message string.
     *
     * @return Request
     */
    public static function parseServerRequest($message)
    {
        $data = _parse_message($message);
        $matches = [];
        if (!preg_match('/^[\S]+\s+([a-zA-Z]+:\/\/|\/).*/', $data['start-line'], $matches)) {
            throw new \InvalidArgumentException('Invalid request string');
        }
        $parts = explode(' ', $data['start-line'], 3);
        $version = isset($parts[2]) ? explode('/', $parts[2])[1] : '1.1';
        //解析出query参数
        if (isset($parts[1])) {
            $pos1 = strpos($parts[1], '?');
            $query = substr($parts[1],$pos1+1, -1);
        } else {
            $query = "";
        }

        $request = new ServerRequest(
            $serverParams = [$query],
            $uploadedFiles = [],
            ($matches[1] === '/' ? _parse_request_uri($parts[1], $data['headers']) : $parts[1]),
            $method = null,
            $body = 'php://input',
            $headers = [],
            $cookies = [],
            $queryParams = [],
            $parsedBody = null,
            $version
        );


//        $request = new ServerRequest(
//            $parts[0],
//            $matches[1] === '/' ? _parse_request_uri($parts[1], $data['headers']) : $parts[1],
//            $data['headers'],
//            $data['body'],
//            $version
//        );

        return $matches[1] === '/' ? $request : $request->withRequestTarget($parts[1]);
    }
}
