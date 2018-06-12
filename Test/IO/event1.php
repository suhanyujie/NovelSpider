<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/6/9
 * Time: 下午3:35
 */


$eventBase = new EventBase();
$timer = new Event($eventBase, -1, Event::TIMEOUT | Event::PERSIST, function (){
    echo microtime(true)." : 歼15，滑跃，起飞！".PHP_EOL;
});
$tick = 0.5;
$timer->add($tick);
$eventBase->loop();

