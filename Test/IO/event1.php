<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/11/11
 * Time: 上午11:04
 */


$method = Event::getSupportedMethods();
print_r( $method );


$eventBase = new EventBase();
$event = new Event($eventBase,SIGTERM,Event::SIGNAL,function () {
    echo "signal term\n";
});
$event->add();
echo "IO模型的类型：{$eventBase->getMethod()}\n";
echo "进入循环\n";
$eventBase->loop();