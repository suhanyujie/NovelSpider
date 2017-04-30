<?php
require __DIR__."/../../vendor/autoload.php";

use Novel\NovelSpider\Controller\ListSpider;
use Novel\NovelSpider\Controller\ListStore;

$service = new ListSpider();
$listArr = $service->getList();
$storeObj = new ListStore();
$storeObj->storeAll($listArr);








