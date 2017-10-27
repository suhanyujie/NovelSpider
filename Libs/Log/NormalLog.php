<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/10/26
 * Time: 下午10:06
 */

namespace Libs\Log;

use Libs\Helper\PublicFunction;
use Libs\Helper\FileFunction;


class NormalLog
{
    /**
     * @desc 构造函数
     *  1.获取日志文件名
     *  2.判断文件是否存在，不存在，则创建
     *  3.将日志内容写入文件当中
     *
     * NormalLog constructor.
     * @param string $businessName 日志业务名，如果不存在，则新建文件夹
     */
    public function __construct($businessName='')
    {
        $fileName = $this->getFileName();
        // 创建文件夹
        FileFunction::mkDirs(dirname($fileName));
        // 创建文件
        touch($fileName);
        chmod($fileName,0777);
    }
    
    /**
     * @desc 获取日志文件名 全路径
     * @param string $businessName
     * @return string
     */
    public function getFileName($businessName = 'NormalLog')
    {
        $dateEleArr = [
            date('Y'),
            date('m'),
        ];
        $fileName = PublicFunction::storage_path() . '/' . $businessName . '/' . implode('/', $dateEleArr) . '/' . date('d') . '.log';
        
        return $fileName;
    }
    
    /**
     * @desc 通过当前日期生成日志文件
     */
    public function getDateString()
    {
        return date('Y-m-d');
    }
}