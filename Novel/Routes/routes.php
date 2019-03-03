<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/4
 * Time: 上午9:33
 */
use League\Route\Router;
use Libs\Core\Store\PrivateStorage;

$container = PrivateStorage::$container;
if (is_null(PrivateStorage::$router)) {
    PrivateStorage::$router =
    $router = $container->make(Router::class);
    //$router = new Router();
} else {
    $router = PrivateStorage::$router;
}

$router->get('/', 'Novel\Controllers\Access\LoginController::login');
//$router->get('/home', 'Novel\Controllers\Access\LoginController::login');


//Macaw::get('/', 'Novel\Controllers\Access\LoginController@login');

