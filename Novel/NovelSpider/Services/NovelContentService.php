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

class NovelContentService
{
    /**
     * @var NovelContentModel
     */
    protected $contentModel;

    protected static $sData = [];

    protected $data = [];

    public function getDetail($oneTaskData=[], $taskId=0)
    {
        $novel = new Test();
        // 获取详情页的结果
        $detailInfo = $novel->getDetail($oneTaskData);
        if ($detailInfo['status'] != 1) {
            echo $detailInfo['message'];
            return $detailInfo;
        }
        if ($oneTaskData['chapter'] <= 0) {
            echo $message = '章节为0，不是正常的正文内容！'.PHP_EOL;
            return ['status'=>2, 'message'=>$message];
        }
        if (!isset(self::$sData['contentModel'])) {
            $this->contentModel =
            $contentModel = new NovelContentModel;
        } else {
            $contentModel = $this->contentModel;
        }
        $detailData = $detailInfo['data'];
        $curTime = date('Y-m-d H:i:s');
        $detailData = [
            'novel_id'    => $oneTaskData['novel_id'],
            'list_id'     => $oneTaskData['id'],
            'chapter'     => $oneTaskData['chapter'],
            'title'       => $oneTaskData['title'],
            'content'     => $detailData['content'],
            'add_time'    => $curTime,
            'delete_flag' => 0,
            'err_flag'    => 0,
        ];
        $insertRes = $contentModel->create($detailData);
        var_dump($insertRes);return;



        $saveResult = $novel->detailInsertOrUpdate([
            'where' => [
                ['chapter','=',$oneTaskData['chapter'] ],
            ],
            'data'  => $detailData,
        ]);
        if ($saveResult['status'] != 1) {
            echo $saveResult['message'] . PHP_EOL;
            return $saveResult;
        }
        echo date('Y-m-d H:i:s').'--->'.$saveResult['message'].PHP_EOL;
        var_dump($saveResult['data']);
    }
}
