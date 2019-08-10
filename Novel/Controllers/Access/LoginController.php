<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/7
 * Time: 下午2:44
 */

namespace Novel\Controllers\Access;

use Novel\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * /Access/Login/login
     * @return string
     */
    public function login()
    {
        $request = $this->request;
        $arr = [
            'status'=>1,
            'msg'=>'123123 test',
        ];
        $responseStr = $this->json($arr);
        return $responseStr;
    }
}
