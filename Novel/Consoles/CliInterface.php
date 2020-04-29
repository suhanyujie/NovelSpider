<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-04-29
 * Time: 16:50
 */

namespace Novel\Consoles;

/**
 * 命令行处理接口
 * Interface CliInterface
 * @package Novel\Consoles
 */
interface CliInterface
{
    public function handle();
}