<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/3/11
 * Time: 14:57
 */

namespace Novel\Controllers\Spider;


use Novel\Controllers\Controller;

class MainListController extends Controller
{
    public function list()
    {
        $dataArr = [
            [
                'name'=>'samuel1',
                'email'=>'sa@qq.com',
                'createTime'=>'2019-04-05 13:07:34',
            ],
            [
                'name'=>'samuel1',
                'email'=>'sa@qq.com',
                'createTime'=>'2019-04-05 13:07:34',
            ],
            [
                'name'=>'samuel1',
                'email'=>'sa@qq.com',
                'createTime'=>'2019-04-05 13:07:34',
            ],
        ];

        return [
            'status' => 1,
            'data'   => $dataArr,
        ];
    }
}
