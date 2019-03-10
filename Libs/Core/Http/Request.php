<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/8
 * Time: 上午9:23
 */

namespace Libs\Core\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Libs\Core\Http\Request as RequestTool;
use Libs\Core\Http\ConfigLoader;

class Request implements RequestInterface
{
    private $headers = [];

    // 存放[小写字母key]对应[原始的key]的数组
    private $headerNames = [];
    private $protocolVersion = "1.1";
    private $stream;

    /**
     * @desc 实例化时，将http相关数据进行对应的加载
     */
    public function __construct($data=[])
    {

    }

    public function handleRequest($requestUri='')
    {
        try {
            $controllerInfo = RequestTool::getControllerInfo($requestUri);
            $controller = RequestTool::getControllerIns($controllerInfo['cPath']);
            $result = $controller->{$controllerInfo['a']}();
        }catch (\Exception $e) {
            return ['status'=>$e->getCode(),'msg'=>$e->getMessage()];
        }

        return $result;
    }

    //workerman中将uri信息解析为控制器和方法名
    public static function getControllerInfo($uri='')
    {
        $defaultController = ConfigLoader::config('access.http@defaultController');
        $defaultAction = ConfigLoader::config('access.http@defaultAction');
        $arr = explode('?', $uri);
        $uri = trim($arr[0],'/');
        $action = substr($uri, strrpos($uri,'/')+1);
        $action = empty($action) ? $defaultAction : $action;
        if (empty($uri)) {
            $uri = "{$defaultController}/{$defaultAction}";
        }
        $reqArr = explode('/', $uri);
        array_pop($reqArr);
        $conPath = "";
        if (count($reqArr) > 1) {
            $len = count($reqArr);
            foreach ($reqArr as $k=>$item) {
                $item = ucwords($item);
                if ($k == ($len-1)) {
                    $conPath .= "/{$item}Controller";
                    break;
                }
                $conPath .= "/{$item}";
            }
        }
        $retArr = [
            'c'     => $reqArr[0] ?? $defaultController,
            'a'     => strtolower($action) ?? $defaultAction,
            'cPath' => $conPath,
        ];

        return $retArr;
    }


    /**
     * @desc 获取实例化控制器
     */
    public static function getControllerIns($cPath='')
    {
        $basePath = ROOT."/Novel/Controllers";
        $baseNamespace = "Novel\Controllers";
        $className = sprintf("%s%s", $baseNamespace,str_replace('/','\\', $cPath));
        if (!class_exists($className)) {
            throw new \Exception("controller {$className} not found!", -2);
        }

        $controllerIns = new $className();

        return $controllerIns;
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     * 获取HTTP协议的版本的字符串
     * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
     * 字符串必须只包含HTTP协议的版本号 例如"1.1" "1.0"
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     * 返回 指定的HTTP协议版本的实例
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new protocol version.
     *
     * @param string $version HTTP protocol version
     * @return static
     */
    public function withProtocolVersion($version)
    {
        if ($version == $this->protocolVersion) {
            return $this;
        }
        $instance = clone $this;
        $instance->protocolVersion = $version;
        return $instance;
    }

    /**
     * Retrieves all message header values.
     * 检索所有的 header头的值
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     * 当header通过报文发送的时候,key代表header头的名字,并且
     * 每个值是一个跟header相关的字符串数组
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve the
     * exact case in which headers were originally specified.
     *
     * @return string[][] Returns an associative array of the message's headers. Each
     *     key MUST be a header name, and each value MUST be an array of strings
     *     for that header.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     * 通过不区分大小写的名字来检查一个header头是否存在
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($name)
    {
        $headerKey = strtolower($name);
        return isset($this->headerNames[$headerKey]);
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     * 通过给定的不区分大小写的name获取对应的header头消息
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     * 这个方法返回一个该name的header所有的值(作为数组)
     * If the header does not appear in the message, this method MUST return an
     * empty array.
     * 如果这个header没有出现在消息中,这个方法必须返回一个空数组
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name)
    {
        if ($this->hasHeader($name)) {
            $headerKey = $this->headerNames[strtolower($name)];
            return $this->headers[$headerKey];
        } else {
            return [];
        }
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     * 获取一个单个的header值,它的值是逗号分隔的字符串
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method MUST return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method MUST return an empty string.
     */
    public function getHeaderLine($name)
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     * Return an instance with the provided value replacing the specified header.
     * 返回一个规定的header以及对应值value的实例
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new and/or updated header and value.
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value)
    {
        if (!is_array($value)){
            $value = [$value];
        }
        $value = $this->trimHeaderValues($value);
        $normalized = strtolower($name);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $name;
        $new->headers[$normalized] = $value;
        return $new;
    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new header and/or value.
     *
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return static
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader($name, $value)
    {
        if (!is_array($value)){
            $value = [$value];
        }
        $value = $this->trimHeaderValues($value);
        $new = clone $this;
        $lowerName = strtolower($name);
        if (isset($this->headerNames[$lowerName])) {
            $new->headerNames[$lowerName] = $name;
            $new->headers[$name] = array_merge($this->headers[$name], $value);
        } else {
            $new->headerNames[$lowerName] = $name;
            $new->headers[$name] = $value;
        }
        return $new;
    }

    /**
     * Return an instance without the specified header.
     *
     * Header resolution MUST be done without case-sensitivity.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that removes
     * the named header.
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return static
     */
    public function withoutHeader($name)
    {
        $lowerName = strtolower($name);
        if (isset($this->headerNames[$lowerName])) {
            $new = clone $this;
            $headerName = $new->headerNames[$lowerName];
            unset($new->headerNames[$lowerName], $new->headers[$name]);
        } else {
            $new = $this;
        }
        return $new;
    }

    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody()
    {
        // TODO: Implement getBody() method.
    }

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param StreamInterface $body Body.
     * @return static
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body)
    {
        // TODO: Implement withBody() method.
    }

    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget()
    {
        // TODO: Implement getRequestTarget() method.
    }

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     * @param mixed $requestTarget
     * @return static
     */
    public function withRequestTarget($requestTarget)
    {
        // TODO: Implement withRequestTarget() method.
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod()
    {
        // TODO: Implement getMethod() method.
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request method.
     *
     * @param string $method Case-sensitive method.
     * @return static
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod($method)
    {
        // TODO: Implement withMethod() method.
    }

    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri()
    {
        // TODO: Implement getUri() method.
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        // TODO: Implement withUri() method.
    }

    /**
     * @desc: 去除消息体两边的空白字符
     * @author:Samuel Su(suhanyu)
     * @date:17/8/1
     * @param String $param
     * @return Array
     */
    private function trimHeaderValues(array $values) {
        return array_map(function($value){
            return trim($value);
        }, $values);
    }
}
