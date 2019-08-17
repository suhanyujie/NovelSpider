<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/7
 * Time: 下午2:44
 */

namespace Novel\Controllers;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response as ServerResponse;
use Libs\Core\Store\PrivateStorage;
use Libs\Helper\Traits\Response as ResponseToolTrait;
use Libs\Helper\Traits\HttpRequst as HttpRequstTrait;

abstract class Controller
{
    protected $request;

    protected $response;

    protected $data;

    use ResponseToolTrait, HttpRequstTrait;

    /**
     * @desc
     */
    public function __construct()
    {
//        $container = PrivateStorage::$container;
//        $this->request = $container->make('ServerRequest');
//        $this->response = $container->make('ServerResponse');
    }

    public function setRequestData($data = [])
    {
        $this->data['get'] = isset($data['get']) ? $data['get'] : [];
        $this->data['post'] = isset($data['post']) ? $data['post'] : [];
        $this->data['request'] = isset($data['request']) ? $data['request'] : [];
        $this->data['server'] = isset($data['server']) ? $data['server'] : [];
        $this->data['cookie'] = isset($data['cookie']) ? $data['cookie'] : [];
        $this->data['files'] = isset($data['files']) ? $data['files'] : [];
    }
}
