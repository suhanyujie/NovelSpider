<?php
/**
 * @desc: 小说列表的存储到数据库
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 17/4/30
 * Time: 下午5:27
 */
namespace Novel\NovelSpider\Controller;

use Novel\NovelSpider\Models\ListModel;
use Tool\Helper\NumberTransfer;

class ListStore
{

    /**
     * @desc: 储存列表的章节数据
     * @author:Samuel Su(suhanyu)
     * @date:17/4/30
     * @param int $id
     * @param array $paramArr
     * @return void
     */
    public function storeAll($id, $paramArr) {
        // var_dump(array_slice($paramArr,0,5));exit('下午5:33');
        $listModel = new ListModel();
        if(!$paramArr)return false;
        foreach($paramArr as $k=>$row){
            $data = [
                'novel_id'=>$id,// 2 大宋王侯 1俗人回档
                'url'=>$row['link'],
                'title'=>$row['title'],
                'chapter'=>NumberTransfer::checkNatInt($row['title']),
                'flag'=>0,
            ];
            $listModel->insertData($data);
        }
        echo '完成的章数为：'.count($paramArr);
    }
}
