<?php
require __DIR__."/../../vendor/autoload.php";

use Novel\NovelSpider\Controller\ListSpider;
use Novel\NovelSpider\Controller\ListStore;

$fictionList = [
    1 => [
        'fictiona_name' => '俗人回档',
        'list_url'      => 'http://www.xxbiquge.com/3_3482/',
        'base_url'      => 'http://www.xxbiquge.com',
    ],
];
if ($fictionList) {
    foreach ( $fictionList as $k => $item ) {
        $service = new ListSpider($item);
        $listArr = $service->getList();
        $storeObj = new ListStore();
        $storeObj->storeAll($k, $listArr);
        break;
    }
}









