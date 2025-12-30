<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index', ['filter' => 'ratelimit:30,60']);
$routes->post('/login', 'AuthController::prosesLogin', ['filter' => 'ratelimit:5,60']);
$routes->get('/forgot-password', 'AuthController::forgotPassword', ['filter' => 'ratelimit:10,60']);
$routes->post('reset-password', 'AuthController::resetPassword', ['filter' => 'ratelimit:5,60']);
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => ['auth', 'ratelimit:100,60']], static function ($routes) {
    // Routes ke halaman dashboard
    $routes->get('/dashboard', 'Dashboard\DashboardController::index', ['filter' => 'ratelimit:100,60']);

    // Routes untuk module user management
    $routes->group('/users', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
        $routes->get('', 'AppSetup\Users\UsersController::index');
        $routes->get('add', 'AppSetup\Users\UsersController::addUser');
        $routes->post('save', 'AppSetup\Users\UsersController::saveUser');
        $routes->post('table', 'AppSetup\Users\UsersController::loadTable');
        $routes->get('get/(:any)', 'AppSetup\Users\UsersController::getUser/$1');
        $routes->get('export', 'AppSetup\Users\UsersController::exportData');
    });

    // Site Setting
    $routes->get('/site-setting', 'SiteSetting\SiteSettingController::index', ['filter' => ['role:superadmin,admin', 'ratelimit:100,60']]);
});
