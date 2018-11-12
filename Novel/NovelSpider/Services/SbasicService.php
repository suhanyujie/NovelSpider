<?php
/**
 * @desc: 基础的service 类
 * - 构造函数中,需要传入小说id,new的时候,去除对应的选择器/匹配正则 等信息
 * - 主要目的在于,每一个小说主页中的列表需要的匹配正则/选择器不一样,随意将这部分 进行抽象提取
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/4/26
 * Time: 上午8:06
 */


namespace Novel\NovelSpider\Services;


abstract class SbasicService
{
    /**
     * SbasicService constructor.
     * @desc 需要传入小说id,new的时候,去除对应的选择器/匹配正则 等信息
     */
    abstract public function __construct(  $novelId );

    /**
     * @desc 每一个小说主页中的列表需要的匹配正则/选择器不一样,随意将这部分 进行抽象提取
     */
    abstract public function setListPattern();


}// end of class