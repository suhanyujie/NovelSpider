<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/5/17
 * Time: 18:52
 */

namespace Novel\NovelSpider\Services;

use Novel\NovelSpider\Controller\Test;
use Novel\NovelSpider\Models\NovelContentModel;
use Novel\NovelSpider\Models\NovelListModel;

class NovelContentService
{
    /**
     * @var NovelContentModel
     */
    protected $contentModel;

    protected static $sData = [];

    protected $data = [];

    public function __construct()
    {

    }

    /**
     * 抓取详情
     * @param array $oneTaskData
     * array(10) {
        ["id"]=>
        int(2462)
        ["novel_id"]=>
        int(6)
        ["name"]=>
        string(16) "第2118章重磅"
        ["chapter_num"]=>
        int(2)
        ["url"]=>
        string(44) "https://www.biquge5.com/1_1216/19896689.html"
        ["desc"]=>
        string(0) ""
        ["flag"]=>
        int(2)
        ["err_flag"]=>
        int(0)
        ["add_time"]=>
        string(19) "2019-04-27 18:04:40"
        ["update_time"]=>
        string(19) "2019-04-27 18:04:40"
        }
     *
     * @param int $taskId
     * @return array|bool|void
     * @throws \Exception
     */
    public function crawlingGetDetail($oneTaskData=[], $taskId=0)
    {
        if (empty($oneTaskData)) {
            return ['status'=>101, 'message'=>'task参数为空！没有任务数据'];
        }
        $novel = new Test();
        // 获取详情页的结果
        $detailInfo = $novel->getDetail($oneTaskData);
        if ($detailInfo['status'] != 1) {
            echo $detailInfo['message'];
            return $detailInfo;
        }
        if ($oneTaskData['chapter_num'] <= 0) {
            echo $message = '章节为0，不是正常的正文内容！'.PHP_EOL;
            return ['status'=>2, 'message'=>$message];
        }
        if (!isset(self::$sData['contentModel'])) {
            $this->contentModel =
            $contentModel = new NovelContentModel;
        } else {
            $contentModel = $this->contentModel;
        }
        $content = $detailInfo['data'];
        $curTime = date('Y-m-d H:i:s');
        $detailData = [
            'novel_id'    => $oneTaskData['novel_id'],
            'list_id'     => $oneTaskData['id'],
            'chapter'     => $oneTaskData['chapter_num'],
            'title'       => $oneTaskData['name'],
            'content'     => $content,
            'add_time'    => $curTime,
            'delete_flag' => NovelContentModel::NOT_DELETE,
            'err_flag'    => 0,
        ];
        $saveResult = $contentModel->detailInsertOrUpdate([
            'where' => [
                ['novel_id', '=', $oneTaskData['novel_id'] ],
                ['chapter', '=', $oneTaskData['chapter_num'] ],
            ],
            'data'  => $detailData,
        ]);
        if ($saveResult['status'] != 1) {
            echo "异常\t".$saveResult['message'] . PHP_EOL;
        } else {
            // 将 list 表中的内容的 flag 更新
            if (empty($this->data['listModel'])) {
                $listModel = new NovelListModel();
            } else {
                $listModel = $this->data['listModel'];
            }
            $oneNovel = $listModel->find($oneTaskData['id']);
            $oneNovel->flag = 1;
            $oneNovel->update_time = date('Y-m-d H:i:s');
            $oneNovel->save();
        }

        echo date('Y-m-d H:i:s').' ---> '.$saveResult['message'].PHP_EOL;

        return $saveResult;
    }
}
