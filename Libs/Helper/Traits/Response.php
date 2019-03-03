<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2019-03-03
 * Time: 19:24
 */

namespace Libs\Helper\Traits;


trait Response
{

    /**
     * @desc
     */
    public function json($dataArr=[]):string
    {
        return json_encode($dataArr, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }
}