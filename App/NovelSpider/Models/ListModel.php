<?php
namespace Novel\NovelSpider\Models;


class ListModel extends AbstractModel {

    protected $table = 'novel_list';

    // 单条插入
    public function insertData($array=[]){
        foreach($array as $k=>$v){
            if(strtolower($k) === $this->pk) {
                $this->variables[$this->pk] = $v;
            }else {
                $this->variables[$k] = addslashes($v);
            }
        }
        $this->Create();
    }
    // 多条插入
    public function mutipleInsert(){

    }
    // where查询
    public function getAll($array = []){
        if(!$array)return false;
        $where = '';
        $whereArr = [];
        foreach($array as $k=>$v){
            // $where .= ':'.$k
            $whereArr[] =  $k.'='.':'.$k;
        }
        $where .= implode(' AND ',$whereArr);
        $db = new \DB();
        $res = $db->query("SELECT * FROM ".$this->table." WHERE ".$where, $array);
        return $res;
    }





}// end of class

