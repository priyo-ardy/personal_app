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
        $routes->post('save', 'AppSetup\Users\UsersController::saveUser', ['filter' => 'ratelimit:3,60']);
        $routes->post('table', 'AppSetup\Users\UsersController::loadTable');
        $routes->get('get/(:any)', 'AppSetup\Users\UsersController::getUser/$1');
        $routes->get('show/(:any)', 'AppSetup\Users\UsersController::showUser/$1');
        $routes->post('update', 'AppSetup\Users\UsersController::updateData', ['filter' => 'ratelimit:3,60']);
        $routes->post('disable', 'AppSetup\Users\UsersController::disableData', ['filter' => 'ratelimit:3,60']);
        $routes->post('prev', 'AppSetup\Users\UsersController::prevData');
        $routes->post('next', 'AppSetup\Users\UsersController::nextData');
        $routes->post('mass-delete', 'AppSetup\Users\UsersController::massDelete');
        $routes->get('export', 'AppSetup\Users\UsersController::exportData');
    });

    // Routes untuk module department
    $routes->group('/department', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
        $routes->get('', 'AppSetup\Department\DepartmentController::index');
        $routes->post('save', 'AppSetup\Department\DepartmentController::saveData', ['filter' => 'ratelimit:3,60']);
        $routes->post('table', 'AppSetup\Department\DepartmentController::loadTable');
        $routes->get('get/(:any)', 'AppSetup\Department\DepartmentController::getData/$1');
        $routes->post('update', 'AppSetup\Department\DepartmentController::updateData', ['filter' => 'ratelimit:3,60']);
        $routes->post('mass-delete', 'AppSetup\Department\DepartmentController::massDelete', ['filter' => 'ratelimit:3,60']);
        $routes->get('export', 'AppSetup\Department\DepartmentController::exportData', ['filter' => 'ratelimit:3,60']);
    });

    // Routes untuk module section
    $routes->group('/section', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
        $routes->get('', 'AppSetup\Section\SectionController::index');
        $routes->post('save', 'AppSetup\Section\SectionController::save', ['filter' => 'ratelimit:3,60']);
        $routes->post('table', 'AppSetup\Section\SectionController::loadTable');
        $routes->get('get/(:any)', 'AppSetup\Section\SectionController::get/$1');
        $routes->post('update', 'AppSetup\Section\SectionController::update', ['filter' => 'ratelimit:3,60']);
        $routes->post('delete', 'AppSetup\Section\SectionController::delete', ['filter' => 'ratelimit:3,60']);
        $routes->get('export', 'AppSetup\Section\SectionController::export', ['filter' => 'ratelimit:3,60']);
    });

    // Routes untuk employee grade
    $routes->group('/employee_grade', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
        $routes->get('', 'AppSetup\EmployeeGrade\EmployeeGradeController::index');
        $routes->post('save', 'AppSetup\EmployeeGrade\EmployeeGradeController::saveData', ['filter' => 'ratelimit:3,60']);
        $routes->post('table', 'AppSetup\EmployeeGrade\EmployeeGradeController::loadTable');
        $routes->get('get/(:any)', 'AppSetup\EmployeeGrade\EmployeeGradeController::getData/$1');
        $routes->post('update', 'AppSetup\EmployeeGrade\EmployeeGradeController::updateData', ['filter' => 'ratelimit:3,60']);
        $routes->post('delete', 'AppSetup\EmployeeGrade\EmployeeGradeController::deleteData', ['filter' => 'ratelimit:3,60']);
        $routes->get('export', 'AppSetup\EmployeeGrade\EmployeeGradeController::exportData', ['filter' => 'ratelimit:3,60']);
    });

    // Routes untuk employee category
    $routes->group('/employee_category', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
        $routes->get('', 'AppSetup\EmployeeCategory\EmployeeCategoryController::index');
        $routes->post('save', 'AppSetup\EmployeeCategory\EmployeeCategoryController::saveData', ['filter' => 'ratelimit:3,60']);
        $routes->post('table', 'AppSetup\EmployeeCategory\EmployeeCategoryController::loadTable');
        $routes->get('get/(:any)', 'AppSetup\EmployeeCategory\EmployeeCategoryController::getData/$1');
        $routes->post('update', 'AppSetup\EmployeeCategory\EmployeeCategoryController::updateData', ['filter' => 'ratelimit:3,60']);
        $routes->post('delete', 'AppSetup\EmployeeCategory\EmployeeCategoryController::deleteData', ['filter' => 'ratelimit:3,60']);
        $routes->get('export', 'AppSetup\EmployeeCategory\EmployeeCategoryController::exportData', ['filter' => 'ratelimit:3,60']);
    });

    // Site Setting
    $routes->get('/site-setting', 'SiteSetting\SiteSettingController::index', ['filter' => ['role:superadmin,admin', 'ratelimit:100,60']]);
});
