<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('/', 'Home::index');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('system-settings', 'SystemSettingController::index');
    $routes->get('system-settings/(:num)', 'SystemSettingController::show/$1');
    $routes->get('jwt/token', 'JwtTestController::getToken');
    $routes->post('system-settings', 'SystemSettingController::create');
    $routes->put('system-settings/(:num)', 'SystemSettingController::update/$1');
    $routes->delete('system-settings/(:num)', 'SystemSettingController::delete/$1');

});

$routes->group('user', ['namespace' => 'App\Controllers\User'], function($routes){
    $routes->get('email-histories', 'EmailHistoriesController::index');
    $routes->get('email-histories/(:num)', 'EmailHistoriesController::show/$1');
    $routes->post('email-histories', 'EmailHistoriesController::create');
    $routes->put('email-histories/(:num)', 'EmailHistoriesController::update/$1');
    $routes->delete('email-histories/(:num)', 'EmailHistoriesController::delete/$1');
});

$routes->post('login/token', 'Login\LoginController::getToken');

// (:num)
// (:any)
//$1 Tham số thứ nhất bắt được
