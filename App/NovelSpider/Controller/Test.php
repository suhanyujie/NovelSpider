<?php

namespace Novel\NovelSpider\Controller;

use QL\QueryList;

class Test{

    public function getList(){
        $hj = QueryList::Query('http://www.biquwu.cc/biquge/17_17308/',array("list"=>array('.article_texttitleb li:first','html')),'body','utf-8');
        $data = $hj->getData(function($x){
            return $x['list'];
        });
        print_r($data);
        return $data;
    }


}// end of class


