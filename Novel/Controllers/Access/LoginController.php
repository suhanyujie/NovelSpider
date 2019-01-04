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
    public function login(Request $request)
    {
        var_dump($request);
        $arr = [
            'status'=>1,
            'msg'=>'123123 test',
        ];
        //print_r($arr);

        return $arr;
    }
}
