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

abstract class Controller
{
    protected $request;

    protected $response;
    /**
     * @desc
     */
    public function __construct()
    {
        $container = PrivateStorage::$container;
        $this->request = $container->make('ServerRequest');
        $this->response = $container->make('ServerResponse');
    }
}
