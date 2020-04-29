<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-04-29
 * Time: 17:34
 */

namespace Novel\Consoles;


use Novel\Consoles\Exports\ExportNovel;

class Kernel
{
    protected $commands = [
        ExportNovel::class,
    ];

    /**
     * @desc 解析 commands
     */
    public function boot()
    {
        foreach ($this->commands as $commandClass) {
            // todo
        }
    }
}
