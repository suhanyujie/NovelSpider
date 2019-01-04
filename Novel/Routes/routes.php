<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 19/1/4
 * Time: 上午9:33
 */
use NoahBuscher\Macaw\Macaw;

Macaw::get('/', 'Novel\Controllers\Access\LoginController@login');

