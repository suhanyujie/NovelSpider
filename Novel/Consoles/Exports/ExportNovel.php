<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-04-29
 * Time: 16:49
 */

namespace Novel\Consoles\Exports;

use Novel\Consoles\CliInterface;

class ExportNovel implements CliInterface
{
    protected $signature = 'novel:exportTxt';

    /**
     * @desc 获取命令行参数
     */
    public function getArgs()
    {
        
    }

    public function handle()
    {

    }
}

$paramArr = $argv;
