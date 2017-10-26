<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/10/26
 * Time: 下午11:01
 */

namespace Tool\Helper;


class FileFunction
{
    /**
     * @desc 递归的创建目录
     * @param string $dir
     * @return bool
     */
    public static function mkDirs($dir)
    {
        if(!is_dir($dir)){
            if(!self::mkDirs(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0777)){
                return false;
            }
        }
        return true;
    }
}