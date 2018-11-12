<?php
require __DIR__."/../../vendor/autoload.php";

use Novel\NovelSpider\Controller\ListSpider;
use Novel\NovelSpider\Controller\ListStore;
use Libs\Log\NormalLog;
use Novel\NovelSpider\Services\SdealUpdate;

$fictionList = [
    1 => [
        'fictiona_name' => '俗人回档',
        'list_url'      => 'http://www.xxbiquge.com/3_3482/',
        'base_url'      => 'http://www.xxbiquge.com',
    ],
];


// 采集列表
//if ($fictionList) {
//    foreach ( $fictionList as $k => $item ) {
//        $service = new ListSpider($item);
//        $listArr = $service->getList();
//        $storeObj = new ListStore();
//        $storeObj->storeAll($k, $listArr);
//        break;
//    }
//}

// 采集详情内容
//$contentSpider = new \Novel\NovelSpider\Controller\ContentSpider();
//$contentSpider->getContent();

// 测试日志功能
$logger = new NormalLog();
$logger->info('测试-记录 信息');

// 测试 更新小说连载
if ($fictionList) {
    foreach ( $fictionList as $k => $item ) {
        $dealUpdateService = new SdealUpdate($k);
        $dealUpdateService->getInternetUpdate();
        break;
    }
}










