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

class ListStore
{

    /**
     * @desc:
     * @author:Samuel Su(suhanyu)
     * @date:17/4/30
     * @param array $paramArr
     * @return Array
     */
    public function storeAll($paramArr) {
        var_dump(array_slice($paramArr,0,3));exit('下午5:33');
        $listModel = new ListModel();
        if(!$paramArr)return false;
        foreach($paramArr as $k=>$row){
            $data = [
                'novel_id'=>1,// 2 大宋王侯 1俗人回档
                'url'=>$row['link'],
                'title'=>$row['title'],
                'chapter'=>NumberTransfer::checkNatInt($row['title']),
                'flag'=>0,
            ];
            $listModel->insertData($data);
        }
    }


}