<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/7
 * Time: 下午2:44
 */

namespace Novel\Controllers;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

abstract class Controller
{
    /**
     * @desc
     */
    public function __construct(Request $request, Response $response)
    {

    }
}
