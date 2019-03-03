<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/7
 * Time: 下午2:44
 */

namespace Novel\Controllers\Access;

use Novel\Controllers\Controller;
use Zend\Diactoros\Response as ServerResponse;

class LoginController extends Controller
{
    public function login()
    {
        $request = $this->request;
        $arr = [
            'status'=>1,
            'msg'=>'123123 test',
        ];
        $responseStr = json_encode($arr, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $response = new ServerResponse($responseStr,
            200,
            []
        );

        //print_r($arr);

        return $response;
    }
}
