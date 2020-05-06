<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-05-06
 * Time: 20:00
 */

namespace Novel\Consoles\Tools;


use Novel\Consoles\BaseConsole;
use Novel\Consoles\CliInterface;
use Novel\NovelSpider\Models\NovelMainModel;
use Novel\NovelSpider\Services\NovelMainService;

/**
 * 增加一部小说
 * @cmd php Novel/Consoles/index.php novel:addOneNovel https://www.biquwu.cc/biquge/51_51600/
 * Class AddOneNovel
 * @package Novel\Consoles\Tools
 */
class AddOneNovel extends BaseConsole implements CliInterface
{
    protected $data = [];

    protected $signature = 'novel:addOneNovel';

    public function handle()
    {
        global $argv;
        $novelUrl = $argv[2] ?? '';
        if (empty($novelUrl)) throw new \Exception("要抓取的目标小说地址不存在！", -1);
        $baseUrlInfo = pathinfo($novelUrl);
        $baseUrl = dirname($baseUrlInfo['dirname']);
        $service = new NovelMainService();
        $info = $service->getOneNovelInfoByUrl($novelUrl);
        $curDatetime = date('Y-m-d H:i:s');
        $novel = new NovelMainModel;
        $novel->name = $info['name'] ?? '';
        $novel->base_url = $baseUrl;
        $novel->list_url = $novelUrl;
        $novel->novel_status = 3;
        $novel->insert_date = $curDatetime;
        $novel->update_time = $curDatetime;
        $isOk = $novel->save();
    }


}
