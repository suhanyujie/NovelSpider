<?php
namespace Novel\Controllers\Spider;

use Novel\Controllers\Controller;
use Novel\NovelSpider\Services\NovelMainService;

class MainListController extends Controller
{
    public function list(): array
    {
        $svc = new NovelMainService;
        $res = $svc->getMainList();

        return $res;
    }
}
