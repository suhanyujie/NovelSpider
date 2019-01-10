<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/10
 * Time: 上午8:35
 */

namespace Libs\Core\Http;

use GuzzleHttp\Psr7\Request as Psr7Request;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends Psr7Request implements ServerRequestFactoryInterface
{
    /** @var string */
    private $method;

    /** @var null|string */
    private $requestTarget;

    /** @var UriInterface */
    private $uri;

    /**
     * @desc
     */
    public function __construct()
    {

    }

    /**
     * @param string $method
     * @param \Psr\Http\Message\UriInterface|string $uri
     * @param array $serverParams
     * @return ServerRequestInterface
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {

    }
}
