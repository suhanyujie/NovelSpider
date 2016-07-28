<?php
require __DIR__."/../../vendor/autoload.php";

require __DIR__."/../../vendor/indieteq/indieteq-php-my-sql-pdo-database-class/Db.class.php";


$db = new DB();

$data = $db->query("SELECT * FROM tests");

var_dump($data);







