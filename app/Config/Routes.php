<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index', ['filter' => 'ratelimit:30,60']);
$routes->post('/login', 'AuthController::prosesLogin', ['filter' => 'ratelimit:5,60']);
$routes->get('/forgot-password', 'AuthController::forgotPassword', ['filter' => 'ratelimit:10,60']);
