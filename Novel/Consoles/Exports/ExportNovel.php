<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 2020-04-29
 * Time: 16:49
 */

namespace Novel\Consoles\Exports;

use Novel\Consoles\BaseConsole;
use Novel\Consoles\CliInterface;
use Config\Db;
use Novel\NovelSpider\Models\NovelContentModel;
use Novel\NovelSpider\Models\NovelMainModel;

class ExportNovel extends BaseConsole implements CliInterface
{
    protected $data = [];

    protected $signature = 'novel:exportTxt';

    public function handle()
    {
        echo "start\n";
        // 数据库链接初始化
        Db::init();
        // 查询 novel 数据
        $novelId = 10;
        // 查询小说信息
        $mainModel = new NovelMainModel();
        $novels = $mainModel->getList([
            'id'=>$novelId,
        ]);
        $novel = $novels[0] ?? [];
        $this->data['novel'] = $novel;
        // 查询章节数据
        $contentModel = new NovelContentModel();
        $listCollect = $contentModel::getList([
            'novel_id' => $novelId,
            'order_by' => [
                'chapter' => 'asc',
            ],
            'limit'    => 5000,
        ]);
        $list = $listCollect->toArray();
        foreach ($list as $oneChapter) {
            $oneChapterContent = $this->getOneChapterContent($oneChapter);

            // 将数据写入到 txt 文件
            $dest = "dist/test.txt";
            @file_put_contents($dest, $oneChapterContent, FILE_APPEND);
        }
        echo "end\n";
    }


    /**
     * @desc 获取一个章节的组装好的内容
     * @return string
     */
    public function getOneChapterContent($oneChapter = [])
    {
        $content = $oneChapter['title'] ?? '';
        $content .= PHP_EOL;
        // 需要被替换的垃圾字符串
        $rubishStrList= [
            '        ',
            '看更多诱惑小说请关注微信  npxswz各种乡村  都市  诱惑  ',
        ];
        $content .= str_replace($rubishStrList, '', $oneChapter['content'] ?? '');

        return $content;
    }
}
