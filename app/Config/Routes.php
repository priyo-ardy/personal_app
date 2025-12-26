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

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Routes ke halaman dashboard
    $routes->get('/dashboard', 'Dashboard\DashboardController::index');
});
