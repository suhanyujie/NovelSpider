<?php
namespace Novel\NovelSpider\Models;

use Novel\NovelSpider\Models\MyDB;
use Illuminate\Contracts\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ListModel extends AbstractModel {

    protected $table = 'novel_list';
    protected $fillable = [
        'id','novel_id','url','flag','err_flag',
    ];

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
        $whereArr = $arrayNew = [];
        foreach($array as $k=>$v){
            // $where .= ':'.$k
            if(!in_array($k,$this->fillable))continue;
            $whereArr[] =  $k.'='.':'.$k;
            $arrayNew[$k] = $v;
        }
        $orderSql = '';
        if (isset($array['order'])) {
            switch( $array['order'] ){
                case 1:
                    $orderSql = ' ORDER BY id DESC ';
                    break;
            }
        }else{
            $orderSql = ' ORDER BY id DESC ';
        }

        $num = isset($array['num']) ? $array['num'] : 1000;
        $where .= implode(' AND ',$whereArr);
        if($whereArr){
            $where = ' AND '.$where;
        }
        $db = new MyDB();
        $sql = "SELECT * FROM ".$this->table." WHERE 1 ".$where. $orderSql.' LIMIT '.$num;
        var_dump($sql,$arrayNew);
        $res = $db->query("SELECT * FROM ".$this->table." WHERE 1 ".$where. $orderSql.' LIMIT '.$num, $arrayNew);

        return $res;
    }// end of function

    /**
     * 批量更新,且只支持更新1个字段!!!
     * @param $paramArr
     * @return boolean
     */
    public function updateMultiple($paramArr){
        /*$fields = [];
        foreach($paramArr as $k=>$v){
            if(!in_array($k,$this->fillable))continue;
            $fields[$k] = $v;
        }*/
        $db = new MyDB();
        //$flag = $this->getBindQuery("UPDATE ".$this->table." SET flag=? where id=?", array($paramArr['flag'],$paramArr['id']));
        $fieldName = array_keys($paramArr['fieldParam']);
        $fieldName = $fieldName[0];
        $conditionFieldName = array_keys($paramArr['conditionParam']);
        $conditionFieldName = $conditionFieldName[0];
        $updateSql = 'UPDATE '.$this->table.' SET '.$fieldName.' = CASE '.$conditionFieldName;
        $sqlPart1 = '';
        foreach ($paramArr['conditionParam'][$conditionFieldName] as $id => $ordinal) {
            $sqlPart1 .= sprintf(" WHEN %d THEN %d ", $ordinal, $paramArr['fieldParam'][$fieldName]);// 1是指 set的值
        }
        $sqlPart1 .= "END WHERE ".$conditionFieldName." IN (" . implode(',',$paramArr['conditionParam'][$conditionFieldName]) . ")";
        $updateSql .= $sqlPart1;
        //echo $updateSql.PHP_EOL;
        $flag = $db->query($updateSql);

        return $flag;
    }





}// end of class

