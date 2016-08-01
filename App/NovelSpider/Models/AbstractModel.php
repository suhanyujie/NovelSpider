<?php
namespace Novel\NovelSpider\Models;

require __DIR__.'/../../../vendor/indieteq/indieteq-php-my-sql-pdo-database-class/easyCRUD/easyCRUD.class.php';
// require __DIR__.'/../../../vendor/indieteq/indieteq-php-my-sql-pdo-database-class/Db.class.php';


abstract class AbstractModel extends \Crud {

    protected $table = '';
    protected $pk	 = 'id';

    protected $data = [];

    /**
     * @desc insert添加数据
     */
    public static function addData($paramArr){

    }
    /**
     * @desc update数据
     */
    public static function updateData($paramArr){

    }



}// end of class
