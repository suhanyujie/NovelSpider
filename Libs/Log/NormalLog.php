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
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class NormalLog
{
    /**
     * @desc 日志业务 默认值
     * @var string
     */
    protected $businessName = 'NOVEL-LOG-NORMAL';
    protected $logFile = '';
    /**
     * @desc 日志记录器
     * @var string
     */
    protected $log = '';
    
    /**
     * @desc 构造函数
     *  1.获取日志文件名
     *  2.判断文件是否存在，不存在，则创建
     *  3.将日志内容写入文件当中
     *
     * NormalLog constructor.
     * @param string $businessName 日志业务名，如果不存在，则新建文件夹
     */
    public function __construct($businessName = '')
    {
        if (!$businessName) {
            $businessName = $this->businessName;
        }
        $fileName = $this->getFileName();
        // 创建文件夹
        FileFunction::mkDirs(dirname($fileName));
        // 创建文件
        touch($fileName);
        chmod($fileName, 0777);
        $this->logFile = $fileName;
        
        $this->log = new Logger($businessName);
        $this->log->pushHandler(new StreamHandler($this->logFile), Logger::INFO);
    }
    
    /**
     * @desc 写日志 info级别的日志
     * @param string $content
     * @return void
     */
    public function info($content = '')
    {
        $this->log->info($content);
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