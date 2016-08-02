<?php
namespace Novel\NovelSpider\Models;

require __DIR__.'/../../../vendor/indieteq/indieteq-php-my-sql-pdo-database-class/easyCRUD/easyCRUD.class.php';

use Novel\NovelSpider\Models\MyDB;

abstract class AbstractModel extends \Crud {

    protected $table = '';
    protected $pk	 = 'id';

    protected $username = 'root';
    protected $password = '123456';
    protected $_timeout = 0;
    protected $engine = 'mysql';
    protected $charset = 'utf8';
    protected $db;

    protected $dbOri;
    protected $sqlComment = '';
    protected $master;
    protected $_debugFlag=false;
    public $variables;
    protected $settings;

    protected $servers = [
        'master'=>[
            'host' => '127.0.0.1',
            'database' => 'bbs_test',
        ],
    ];
    /**
     *  pdo预处理对象
     * @var object
     */
    protected $stmtQuery;

    // 重写 __construct 方法
    public function __construct($data = array()) {
        $this->db =  new MyDB();
        $this->variables  = $data;
    }

    /**
     * @desc insert添加数据
     */
    public function insertData($array = []){
        if(!$array)return false;
        foreach($array as $k=>$v){
            if(strtolower($k) === $this->pk) {
                $this->variables[$this->pk] = $v;
            } else {
                $this->variables[$k] = addslashes($v);// addslashes($v)
            }
        }
        $this->create();
    }

    // where查询
    public function getAll($array = []){
        if(!$array)return false;
        $where = '';
        $whereArr = [];
        foreach($array as $k=>$v){
            // $where .= ':'.$k
            if(!in_array($k,$this->fillable))continue;
            $whereArr[] =  $k.'='.':'.$k;
        }
        $num = isset($array['num']) ? $array['num'] : 1000;
        $where .= implode(' AND ',$whereArr);
        $db = $this->db;
        $res = $db->query("SELECT * FROM ".$this->table." WHERE ".$where.' LIMIT '.$num, $array);
        return $res;
    }


    /**
     * @param $sql
     */
    public function chooseDbConn(){
        $this->createDbConn('master');
    }

    /**
     * 创建数据库链接
     *
     * @param enum $type {master|slave}
     * @return PDO
     */
    protected function createDbConn($dbType = 'master')
    {
        if (empty($this->$dbType)) {
            $dns = $this->engine . ':dbname=' . $this->servers[$dbType]['database'] .
                ';host=' . $this->servers[$dbType]['host'];
            try {
                $lnParam = array();
                //设置连接的超时时间
                if($this->_timeout)$lnParam[\PDO::ATTR_TIMEOUT] = $this->_timeout;

                $this->$dbType = new \PDO($dns, $this->username, $this->password,$lnParam);
                if ($this->charset) {
                    $this->$dbType->exec("SET NAMES '{$this->charset}'");
                }
            } catch (PDOException $e) {
                if($this->_debugFlag){
                    trigger_error($e->getMessage(), E_USER_WARNING);
                }
                return false;
            }
        }
        $this->dbOri =& $this->$dbType;
        return true;
    }

    /**
     * 绑定并执行预处理sql查看
     * @param unknown $sql
     * @param unknown $param
     * @return Ambigous <mixed, string>
     */
    public function showBindSql($sql,$param) {
        ##(\?)|(:[0-9a-zA-Z-_]*)#
        $num = preg_match_all('#(\?)#',$sql,$m);
        if ($num !== count($param)) {
            return '参数数目不对';
        }
        foreach ($param as $v) {
            $sql = preg_replace('#(\?)#',$v,$sql,1);
        }
        return $sql;
    }
    /**
     * @desc  绑定并执行预处理操作
     * @param sql $sql
     * @param array $param
     * @param string $dbType
     * @return string
     */
    public function execBind($sql,$param) {
        #链接mysql
        $this->chooseDbConn();
        $this->stmtQuery = $this->dbOri->prepare($this->getBindParams($sql,$param));
        if (!empty($param)) {
            if (array_key_exists(0, $param)) {
                $parametersType = true;
                array_unshift($param, "");
                unset($param[0]);
            } else {
                $parametersType = false;
            }
        }
        #绑值
        foreach ($param as $column => $value) {
            $this->stmtQuery->bindParam($parametersType ? intval($column) : ":" . $column, $param[$column]);
        }
        $query = $this->stmtQuery->execute();
        #重试链接
        $reconnectNum = 1;
        if (empty($query)) {
            //$error = $this->errorInfo();
            if ($reconnectNum < 3 && $error[0] == 'HY000' && in_array($error[1],array(2003,2004,2006,2055,2013))) {
                $this->dbOri = null;
                $reconnectNum ++;
                if ($reconnectNum > 1) {
                    usleep(50000);
                }
                return $this->execBind($sql);
            }
            if($this->_debugFlag){
                trigger_error($error[2], E_USER_WARNING);
            }
        }
        return $query;
    }
    /**
     * 兼容 id in (?)  操作
     * @param string $sql
     * @param string $params
     * @return mixed|string
     */
    private function getBindParams($sql, $params = null)
    {
        $sqlCmm = $this->getSqlComment();
        if (!empty($params)) {
            $sql        = trim($sql);
            $rawStatement = explode(" ", $sql);
            foreach ($rawStatement as $value) {
                if (strtolower($value) == 'in' || strtolower($value) == 'value' ) {
                    return str_replace("(?)", "(" . implode(",", array_fill(0, count($params), "?")) . ")", $sql).$sqlCmm;
                }
            }
        }
        return $sql.$sqlCmm;
    }
    /**
     * 返回 PDOStatement 对象
     * @return
     */
    public  function getStmtQuery(){
        return $this->stmtQuery;
    }
    /**
     * 获取所有数据
     * @param string $sql
     * @param array $param
     * @param string $fetchmode  返回类型   默认PDO::FETCH_ASSOC
     * @return unknown
     */
    public function getBindAll($sql,$param,$fetchmode = PDO::FETCH_ASSOC) {
        $res = $this->execBind($sql,$param);
        if ($res == false) { return false;}
        $resultRow = $this->stmtQuery->fetchAll($fetchmode);
        $this->stmtQuery->closeCursor();
        return $resultRow;
    }
    /**
     * 获取一行数据
     * @param string $sql
     * @param array $param
     * @param string $fetchmode       返回类型   默认PDO::FETCH_ASSOC
     * @return
     */
    public function getBindRow($sql,$param,$fetchmode = PDO::FETCH_ASSOC) {
        $res = $this->execBind($sql,$param);
        if ($res == false) { return false;}
        $resultRow = $this->stmtQuery->fetch($fetchmode);
        $this->stmtQuery->closeCursor();
        return $resultRow;
    }
    /**
     * 获取一列数据
     * @param string $sql
     * @param array $param
     * @param string $fetchmode
     * @return
     */
    public function getBindCol($sql,$param,$colNo=0) {
        $res = $this->execBind($sql,$param);
        if ($res == false) { return false;}
        $resultRow = $this->stmtQuery->fetchAll(PDO::FETCH_COLUMN,$colNo);
        $this->stmtQuery->closeCursor();
        return $resultRow;
    }
    /**
     * 获取一个数据
     * @param string $sql
     * @param array $param
     * @param string $fetchmode
     * @return
     */
    public function getBindOne($sql,$param,$colNo=0) {
        $res = $this->execBind($sql,$param);
        if ($res == false) { return false;}
        $res = $this->stmtQuery->fetchColumn($colNo);
        $this->stmtQuery->closeCursor();
        return $res;
    }
    /**
     * 直接已绑定方式查询
     * @param string $sql
     * @param array $param
     * @param string $fetchmode
     * @return
     */
    public function getBindQuery($sql, $params = null, $fetchmode = \PDO::FETCH_ASSOC)
    {
        $sql        = trim($sql);
        $rawStatement = explode(" ", $sql);
        $res = $this->execBind($sql,$params);
        if ($res == false) { return false;}
        $statement = strtolower($rawStatement[0]);
        if ($statement === 'select' || $statement === 'show') {
            $res = $this->stmtQuery->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            $res = $res;
        } else {
            $res =  NULL;
        }
        $this->stmtQuery->closeCursor();
        return $res;
    }
    /**
     * 得到上一行sql影响的行数
     * @return
     */
    public function getAffectNo(){
        $res = $this->stmtQuery->rowCount();
        $this->stmtQuery->closeCursor();
        return $res;
    }
    /**
     * 插入数据
     * @param string $tableName
     * @param array $params
     * @param string $fetchmode
     * @return void|Ambigous <unknown, NULL>
     */
    public function execBindInsert($tableName, $params, $isMulti=FALSE) {
        $data = $this->getBindInsertParam($params,$isMulti);
        if (!is_array($data) || empty($data)) {
            return ;
        }
        $sql = "INSERT INTO {$tableName} {$data['insertKeys']} VALUES {$data['questionMarks']}";
        return $this->getBindQuery($sql,$data['insertValues']);
    }
    /**
     * @desc     传入一维或者二维数组，获取insert的绑定参数
     * @param    要插入的数据数组
     * @return   插入的字段键名名 insertKeys  (key1, key2,key3,...)
     * @return   绑定的占位符    questionMarks (?,?,?,...) or (?,?,?,...),(?,?,?,...)...
     * @return   插入的数据      insertValues (val1,val2,val3,...)
     */
    function getBindInsertParam($data, $isMulti=false){
        if (!$data || !is_array($data)) return ;
        $insertValues   = array();
        $insertKeys     = array();
        $questionMarks  = array();
        if ($isMulti) {
            // 多维数组
            $cnt  = 0;
            $ques = '';
            foreach ($data as $item) {
                if(empty($item)) continue;
                if(!$cnt) $cnt      = count($item);
                if(!$ques) $ques    = '('.implode(",", array_fill(0, $cnt, '?')) .')';
                $questionMarks[]    = $ques;
                $insertValues       = array_merge($insertValues, array_values($item));
            }
            $insertKeys = array_keys($item);
        } else {
            // 一维数组
            $questionMarks[]    = '('.implode(",", array_fill(0, count($data), '?')) .')';
            $insertValues       = array_values($data);
            $insertKeys         = array_keys($data);
        }
        return array(
            'insertKeys'    => '('.implode(',', $insertKeys).')',
            'questionMarks' => implode(',', $questionMarks),
            'insertValues'  => $insertValues,
        );
    }
    /**
     * 获得注释
     */
    public function getSqlComment(){
        if(!$this->sqlComment){
            $tmpStr = "FROM ZCLOUD ";
            if($_SERVER){
                if(isset($_SERVER["HTTP_HOST"])) $tmpStr .= " DOMAIN:" . $_SERVER["HTTP_HOST"];
                if(isset($_SERVER["HOSTNAME"]))  $tmpStr .= " HOST:" . $_SERVER["HOSTNAME"];
                if(isset($_SERVER["SCRIPT_FILENAME"]))$tmpStr .= " FILE:" . $_SERVER["SCRIPT_FILENAME"];
            }
            $this->sqlComment = "/* {$tmpStr} */";
        }
        //}
        return $this->sqlComment;
    }


    public function bind($para, $value)
    {
        //$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
        $this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . $value;
    }



}// end of class
