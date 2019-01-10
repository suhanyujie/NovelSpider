<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/4
 * Time: 上午9:33
 */
use League\Route\Router;
use Libs\Core\Store\Storage;

if (is_null(Storage::$router)) {
    Storage::$router =
    $router = new Router();
} else {
    $router = Storage::$router;
}

$router->get('/', 'Novel\Controllers\Access\LoginController::login');


//Macaw::get('/', 'Novel\Controllers\Access\LoginController@login');

