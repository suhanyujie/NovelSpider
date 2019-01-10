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

abstract class Controller
{
    /**
     * @desc
     */
    public function __construct(ServerRequest $request, ServerResponse $response)
    {

    }
}
