<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/4/27
 * Time: 13:03
 */

namespace Novel\NovelSpider\Services\Novel;


class Example2Service extends ExampleService
{
    public $mainUrl = 'https://www.biquge5.com/1_1216/';
    protected $baseUrl = 'https://www.biquge5.com';
    public $mainSelector = '#list';

    public function __construct($novelRow)
    {
        var_dump($novelRow);
    }
}
