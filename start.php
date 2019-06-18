#!/usr/bin/env php
<?php
// 启动列表抓取服务
$cmd = "php Novel/NovelSpider/start_list.php start -d";
exec($cmd);
// 启动详情页抓取服务
$cmd = "php Novel/NovelSpider/start_detail.php start -d";
exec($cmd);
// 启动 web api 服务
$cmd = "php Novel/NovelAdmin/index.php start -d";
exec($cmd);

echo PHP_EOL;
echo "启动完成 \n";
