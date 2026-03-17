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

        $routes->group('/spk', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'Module\Mfg\Spk\SpkController::index', ['filter' => 'ratelimit:3,60']);
            $routes->get('add', 'Module\Mfg\Spk\SpkController::add');
        });

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

        // Routes untuk position
        $routes->group('/position', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Position\PositionController::index');
            $routes->get('add', 'AppSetup\Position\PositionController::add');
            $routes->post('save', 'AppSetup\Position\PositionController::saveData', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\Position\PositionController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\Position\PositionController::getData/$1');
            $routes->get('position_detail/(:any)', 'AppSetup\Position\PositionController::getPosition/$1');
            $routes->get('show/(:any)', 'AppSetup\Position\PositionController::showData/$1');
            $routes->post('update', 'AppSetup\Position\PositionController::updateData', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Position\PositionController::deleteData', ['filter' => 'ratelimit:3,60']);
            $routes->post('mass-delete', 'AppSetup\Position\PositionController::massDelete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Position\PositionController::exportData', ['filter' => 'ratelimit:3,60']);
            $routes->get('list', 'AppSetup\Position\PositionController::dataList');
            $routes->post('prev', 'AppSetup\Position\PositionController::prevData');
            $routes->post('next', 'AppSetup\Position\PositionController::nextData');
            $routes->get('seed', 'AppSetup\Position\PositionController::seedData');
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
            $routes->get('seed', 'AppSetup\Department\DepartmentController::seedData');
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
            $routes->get('get_by_dept/(:any)', 'AppSetup\Section\SectionController::get_by_dept/$1');
            $routes->get('seed', 'AppSetup\Section\SectionController::seedData');
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
            $routes->get('seed', 'AppSetup\EmployeeGrade\EmployeeGradeController::seedData');
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
            $routes->get('seed', 'AppSetup\EmployeeCategory\EmployeeCategoryController::seedData');
        });

        // Routes untuk employee rank
        $routes->group('/employee_rank', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\EmployeeRank\EmployeeRankController::index');
            $routes->post('save', 'AppSetup\EmployeeRank\EmployeeRankController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\EmployeeRank\EmployeeRankController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\EmployeeRank\EmployeeRankController::get/$1');
            $routes->post('update', 'AppSetup\EmployeeRank\EmployeeRankController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\EmployeeRank\EmployeeRankController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\EmployeeRank\EmployeeRankController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\EmployeeRank\EmployeeRankController::seedData');
        });

        // Routes untuk employee classs NBHX
        $routes->group('/employee_class_nbhx', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\ClassNBHX\ClassNbhxController::index');
            $routes->post('save', 'AppSetup\ClassNBHX\ClassNbhxController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\ClassNBHX\ClassNbhxController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\ClassNBHX\ClassNbhxController::get/$1');
            $routes->post('update', 'AppSetup\ClassNBHX\ClassNbhxController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\ClassNBHX\ClassNbhxController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\ClassNBHX\ClassNbhxController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\ClassNBHX\ClassNbhxController::seedData');
        });

        // Routes untuk nbhx position category
        $routes->group('/nbhx_position', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\NbhxPosition\NbhxPositionController::index');
            $routes->post('save', 'AppSetup\NbhxPosition\NbhxPositionController::save');
            $routes->post('table', 'AppSetup\NbhxPosition\NbhxPositionController::loadTable', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\NbhxPosition\NbhxPositionController::get/$1');
            $routes->post('update', 'AppSetup\NbhxPosition\NbhxPositionController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\NbhxPosition\NbhxPositionController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\NbhxPosition\NbhxPositionController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\NbhxPosition\NbhxPositionController::seedData');
        });

        // Routes untuk salary rank
        $routes->group('/salary_rank', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\SalaryRank\SalaryRankController::index');
            $routes->post('save', 'AppSetup\SalaryRank\SalaryRankController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\SalaryRank\SalaryRankController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\SalaryRank\SalaryRankController::get/$1');
            $routes->post('update', 'AppSetup\SalaryRank\SalaryRankController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\SalaryRank\SalaryRankController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\SalaryRank\SalaryRankController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\SalaryRank\SalaryRankController::seedData');
        });

        // Routes untuk country
        $routes->group('/country', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Country\CountryController::index');
            $routes->post('save', 'AppSetup\Country\CountryController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\Country\CountryController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\Country\CountryController::get/$1');
            $routes->post('update', 'AppSetup\Country\CountryController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Country\CountryController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Country\CountryController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Country\CountryController::seedData');
        });

        // Routes untuk province
        $routes->group('/province', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Province\ProvinceController::index');
            $routes->post('save', 'AppSetup\Province\ProvinceController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\Province\ProvinceController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\Province\ProvinceController::get/$1');
            $routes->post('update', 'AppSetup\Province\ProvinceController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Province\ProvinceController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Province\ProvinceController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Province\ProvinceController::seedData');
        });

        // Routes untuk city
        $routes->group('/city', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\City\CityController::index');
            $routes->post('save', 'AppSetup\City\CityController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\City\CityController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\City\CityController::get/$1');
            $routes->post('update', 'AppSetup\City\CityController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\City\CityController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\City\CityController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\City\CityController::seedData');
            $routes->get('getCity/(:any)', 'AppSetup\City\CityController::getCity/$1');
        });

        // Routes untuk tempat lahir
        $routes->group('/birth_place', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\TempatLahir\TempatLahirController::index');
            $routes->post('save', 'AppSetup\TempatLahir\TempatLahirController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\TempatLahir\TempatLahirController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\TempatLahir\TempatLahirController::get/$1');
            $routes->post('update', 'AppSetup\TempatLahir\TempatLahirController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\TempatLahir\TempatLahirController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\TempatLahir\TempatLahirController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\TempatLahir\TempatLahirController::seedData');
        });

        // Routes untuk workshop
        $routes->group('/workshop', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Workshop\WorkshopController::index');
            $routes->post('table', 'AppSetup\Workshop\WorkshopController::loadTable');
            $routes->post('save', 'AppSetup\Workshop\WorkshopController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\Workshop\WorkshopController::get/$1');
            $routes->post('update', 'AppSetup\Workshop\WorkshopController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Workshop\WorkshopController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Workshop\WorkshopController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Workshop\WorkshopController::seedData');
        });

        // Routes untuk tonnage
        $routes->group('/tonnage', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Tonnage\TonnageController::index');
            $routes->post('table', 'AppSetup\Tonnage\TonnageController::loadTable');
            $routes->post('save', 'AppSetup\Tonnage\TonnageController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\Tonnage\TonnageController::get/$1');
            $routes->post('update', 'AppSetup\Tonnage\TonnageController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Tonnage\TonnageController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Tonnage\TonnageController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Tonnage\TonnageController::seedData');
        });

        // Route untuk material category
        $routes->group('/material_category', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\MaterialCategory\MaterialCategoryController::index');
            $routes->post('table', 'AppSetup\MaterialCategory\MaterialCategoryController::loadTable');
            $routes->post('save', 'AppSetup\MaterialCategory\MaterialCategoryController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\MaterialCategory\MaterialCategoryController::get/$1');
            $routes->post('update', 'AppSetup\MaterialCategory\MaterialCategoryController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\MaterialCategory\MaterialCategoryController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\MaterialCategory\MaterialCategoryController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\MaterialCategory\MaterialCategoryController::seedData');
        });

        // Route untuk equipment type
        $routes->group('/equipment_type', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\EquipmentType\EquipmentTypeController::index');
            $routes->post('table', 'AppSetup\EquipmentType\EquipmentTypeController::loadTable');
            $routes->post('save', 'AppSetup\EquipmentType\EquipmentTypeController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\EquipmentType\EquipmentTypeController::get/$1');
            $routes->post('update', 'AppSetup\EquipmentType\EquipmentTypeController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\EquipmentType\EquipmentTypeController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\EquipmentType\EquipmentTypeController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\EquipmentType\EquipmentTypeController::seedData');
        });

        // Route untuk UoM module
        $routes->group('/uom', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\UoM\UomController::index');
            $routes->post('table', 'AppSetup\UoM\UomController::loadTable');
            $routes->post('save', 'AppSetup\UoM\UomController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\UoM\UomController::get/$1');
            $routes->post('update', 'AppSetup\UoM\UomController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\UoM\UomController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\UoM\UomController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\UoM\UomController::seedData');
        });

        // Route untuk process routes
        $routes->group('process_routes', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\ProcessRoute\ProcessRouteController::index');
            $routes->post('table', 'AppSetup\ProcessRoute\ProcessRouteController::loadTable');
            $routes->post('save', 'AppSetup\ProcessRoute\ProcessRouteController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\ProcessRoute\ProcessRouteController::get/$1');
            $routes->post('update', 'AppSetup\ProcessRoute\ProcessRouteController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\ProcessRoute\ProcessRouteController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\ProcessRoute\ProcessRouteController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\ProcessRoute\ProcessRouteController::seedData');
        });

        $routes->group('/machine', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Machine\MachineController::index');
            $routes->get('add', 'AppSetup\Machine\MachineController::add');
            $routes->post('save', 'AppSetup\Machine\MachineController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\Machine\MachineController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\Machine\MachineController::get/$1');
            $routes->get('show/(:any)', 'AppSetup\Machine\MachineController::show/$1');
            $routes->post('update', 'AppSetup\Machine\MachineController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Machine\MachineController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->post('prev', 'AppSetup\Machine\MachineController::prev');
            $routes->post('next', 'AppSetup\Machine\MachineController::next');
            $routes->post('mass-delete', 'AppSetup\Machine\MachineController::massDelete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Machine\MachineController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Machine\MachineController::seedData');
        });

        // Route material module
        $routes->group('/material', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Material\MaterialController::index');
            $routes->get('add', 'AppSetup\Material\MaterialController::add');
            $routes->post('save', 'AppSetup\Material\MaterialController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\Material\MaterialController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\Material\MaterialController::get/$1');
            $routes->get('show/(:any)', 'AppSetup\Material\MaterialController::show/$1');
            $routes->post('update', 'AppSetup\Material\MaterialController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Material\MaterialController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->post('prev', 'AppSetup\Material\MaterialController::prev');
            $routes->post('next', 'AppSetup\Material\MaterialController::next');
            $routes->post('mass-delete', 'AppSetup\Material\MaterialController::massDelete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Material\MaterialController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Material\MaterialController::seedData');
            $routes->get('material_list', 'AppSetup\Material\MaterialController::getMaterialList');
        });

        $routes->group('/team_leader', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\TeamLeader\TeamLeaderController::index');
            $routes->post('table', 'AppSetup\TeamLeader\TeamLeaderController::loadTable');
            $routes->post('save', 'AppSetup\TeamLeader\TeamLeaderController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\TeamLeader\TeamLeaderController::get/$1');
            $routes->post('update', 'AppSetup\TeamLeader\TeamLeaderController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\TeamLeader\TeamLeaderController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\TeamLeader\TeamLeaderController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\TeamLeader\TeamLeaderController::seedData');
            $routes->get('employee_list', 'AppSetup\TeamLeader\TeamLeaderController::generateEmployeeList');
        });

        $routes->group('/group_leader', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\GroupLeader\GroupLeaderController::index');
            $routes->post('table', 'AppSetup\GroupLeader\GroupLeaderController::loadTable');
            $routes->post('save', 'AppSetup\GroupLeader\GroupLeaderController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\GroupLeader\GroupLeaderController::get/$1');
            $routes->post('update', 'AppSetup\GroupLeader\GroupLeaderController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\GroupLeader\GroupLeaderController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\GroupLeader\GroupLeaderController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\GroupLeader\GroupLeaderController::seedData');
            $routes->get('employee_list', 'AppSetup\GroupLeader\GroupLeaderController::generateEmployeeList');
        });

        // Route untuk customer category module
        $routes->group('/customer_category', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\CustomerCategory\CustomerCategoryController::index');
            $routes->post('table', 'AppSetup\CustomerCategory\CustomerCategoryController::loadTable');
            $routes->post('save', 'AppSetup\CustomerCategory\CustomerCategoryController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\CustomerCategory\CustomerCategoryController::get/$1');
            $routes->post('update', 'AppSetup\CustomerCategory\CustomerCategoryController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\CustomerCategory\CustomerCategoryController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\CustomerCategory\CustomerCategoryController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\CustomerCategory\CustomerCategoryController::seedData');
        });

        // Routes untuk Customer module
        $routes->group('/customer', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Customer\CustomerController::index');
            $routes->get('add', 'AppSetup\Customer\CustomerController::add');
            $routes->post('save', 'AppSetup\Customer\CustomerController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\Customer\CustomerController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\Customer\CustomerController::get/$1');
            $routes->get('show/(:any)', 'AppSetup\Customer\CustomerController::show/$1');
            $routes->post('update', 'AppSetup\Customer\CustomerController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Customer\CustomerController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->post('prev', 'AppSetup\Customer\CustomerController::prev');
            $routes->post('next', 'AppSetup\Customer\CustomerController::next');
            $routes->post('mass-delete', 'AppSetup\Customer\CustomerController::massDelete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Customer\CustomerController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Customer\CustomerController::seedData');
        });

        // Routes untuk supplier module
        $routes->group('/supplier', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Supplier\SupplierController::index');
            $routes->get('add', 'AppSetup\Supplier\SupplierController::add');
            $routes->post('save', 'AppSetup\Supplier\SupplierController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\Supplier\SupplierController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\Supplier\SupplierController::get/$1');
            $routes->get('show/(:any)', 'AppSetup\Supplier\SupplierController::show/$1');
            $routes->post('update', 'AppSetup\Supplier\SupplierController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Supplier\SupplierController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->post('prev', 'AppSetup\Supplier\SupplierController::prev');
            $routes->post('next', 'AppSetup\Supplier\SupplierController::next');
            $routes->post('mass-delete', 'AppSetup\Supplier\SupplierController::massDelete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Supplier\Supplierontroller::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Supplier\SupplierController::seedData');
        });

        // Routes untuk factory module
        $routes->group('/factory', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Factory\FactoryController::index');
            $routes->post('table', 'AppSetup\Factory\FactoryController::loadTable');
            $routes->post('save', 'AppSetup\Factory\FactoryController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\Factory\FactoryController::get/$1');
            $routes->post('update', 'AppSetup\Factory\FactoryController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Factory\FactoryController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Factory\FactoryController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Factory\FactoryController::seedData');
        });

        // Routes untuk location module
        $routes->group('/location', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\Location\LocationController::index');
            $routes->post('table', 'AppSetup\Location\LocationController::loadTable');
            $routes->post('save', 'AppSetup\Location\LocationController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\Location\LocationController::get/$1');
            $routes->post('update', 'AppSetup\Location\LocationController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\Location\LocationController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\Location\LocationController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\Location\LocationController::seedData');
        });

        // Route untuk employee family relation
        $routes->group('/family_relation', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\FamilyRelation\FamilyRelationController::index');
            $routes->post('table', 'AppSetup\FamilyRelation\FamilyRelationController::loadTable');
            $routes->post('save', 'AppSetup\FamilyRelation\FamilyRelationController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\FamilyRelation\FamilyRelationController::get/$1');
            $routes->post('update', 'AppSetup\FamilyRelation\FamilyRelationController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\FamilyRelation\FamilyRelationController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\FamilyRelation\FamilyRelationController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\FamilyRelation\FamilyRelationController::seedData');
        });

        // Route untuk employee education degree
        $routes->group('/education_degree', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\EducationDegree\EducationDegreeController::index');
            $routes->post('table', 'AppSetup\EducationDegree\EducationDegreeController::loadTable');
            $routes->post('save', 'AppSetup\EducationDegree\EducationDegreeController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\EducationDegree\EducationDegreeController::get/$1');
            $routes->post('update', 'AppSetup\EducationDegree\EducationDegreeController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\EducationDegree\EducationDegreeController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\EducationDegree\EducationDegreeController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\EducationDegree\EducationDegreeController::seedData');
        });

        // Route untuk employee family occupation
        $routes->group('/family_occupation', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\FamilyOccupation\FamilyOccupationController::index');
            $routes->post('table', 'AppSetup\FamilyOccupation\FamilyOccupationController::loadTable');
            $routes->post('save', 'AppSetup\FamilyOccupation\FamilyOccupationController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\FamilyOccupation\FamilyOccupationController::get/$1');
            $routes->post('update', 'AppSetup\FamilyOccupation\FamilyOccupationController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\FamilyOccupation\FamilyOccupationController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\FamilyOccupation\FamilyOccupationController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\FamilyOccupation\FamilyOccupationController::seedData');
        });

        // Route untuk employee facility
        $routes->group('/employee_facility', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\EmployeeFacility\EmployeeFacilityController::index');
            $routes->post('table', 'AppSetup\EmployeeFacility\EmployeeFacilityController::loadTable');
            $routes->post('save', 'AppSetup\EmployeeFacility\EmployeeFacilityController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\EmployeeFacility\EmployeeFacilityController::get/$1');
            $routes->post('update', 'AppSetup\EmployeeFacility\EmployeeFacilityController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\EmployeeFacility\EmployeeFacilityController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\EmployeeFacility\EmployeeFacilityController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\EmployeeFacility\EmployeeFacilityController::seedData');
        });

        // Route untuk uniform type
        $routes->group('/uniform_type', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\UniformType\UniformTypeController::index');
            $routes->post('table', 'AppSetup\UniformType\UniformTypeController::loadTable');
            $routes->post('save', 'AppSetup\UniformType\UniformTypeController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\UniformType\UniformTypeController::get/$1');
            $routes->post('update', 'AppSetup\UniformType\UniformTypeController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\UniformType\UniformTypeController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\UniformType\UniformTypeController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\UniformType\UniformTypeController::seedData');
        });

        // Route untuk uniform size
        $routes->group('/uniform_size', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\UniformSize\UniformSizeController::index');
            $routes->post('table', 'AppSetup\UniformSize\UniformSizeController::loadTable');
            $routes->post('save', 'AppSetup\UniformSize\UniformSizeController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\UniformSize\UniformSizeController::get/$1');
            $routes->post('update', 'AppSetup\UniformSize\UniformSizeController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\UniformSize\UniformSizeController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\UniformSize\UniformSizeController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\UniformSize\UniformSizeController::seedData');
        });

        // Route untuk safety shoes size
        $routes->group('/shoes_size', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\ShoesSize\ShoesSizeController::index');
            $routes->post('table', 'AppSetup\ShoesSize\ShoesSizeController::loadTable');
            $routes->post('save', 'AppSetup\ShoesSize\ShoesSizeController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\ShoesSize\ShoesSizeController::get/$1');
            $routes->post('update', 'AppSetup\ShoesSize\ShoesSizeController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\ShoesSize\ShoesSizeController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\ShoesSize\ShoesSizeController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\ShoesSize\ShoesSizeController::seedData');
        });

        // Routes untuk employee module
        $routes->group('/employee', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'MasterData\Employee\EmployeeController::index');
            $routes->post('new_nik', 'MasterData\Employee\EmployeeController::generateNik', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'MasterData\Employee\EmployeeController::loadTable');
            $routes->get('add', 'MasterData\Employee\EmployeeController::add');
            $routes->post('save', 'MasterData\Employee\EmployeeController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('family/(:any)', 'MasterData\Employee\FamilyController::add/$1');
            $routes->post('family/save', 'MasterData\Employee\FamilyController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('education/(:any)', 'MasterData\Employee\EducationController::add/$1');
            $routes->post('education/save', 'MasterData\Employee\EducationController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('job_data/(:any)', 'MasterData\Employee\EmployeeJobDataController::add/$1');
            $routes->post('job_data/save', 'MasterData\Employee\EmployeeJobDataController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('employee_job_data', 'MasterData\Employee\EmployeeController::employeeUnregisteredJobDataList');
            $routes->get('employee_registered_job_data', 'MasterData\Employee\EmployeeController::employeeRegisteredJobDataList');
            $routes->get('active_employee', 'MasterData\Employee\EmployeeController::emloyeeActive');
        });

        // Route untuk job data action
        $routes->group('/job_data_action', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\JobDataAction\JobDataActionController::index');
            $routes->post('save', 'AppSetup\JobDataAction\JobDataActionController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\JobDataAction\JobDataActionController::loadTable', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\JobDataAction\JobDataActionController::get/$1');
            $routes->post('update', 'AppSetup\JobDataAction\JobDataActionController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\JobDataAction\JobDataActionController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\JobDataAction\JobDataActionController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\JobDataAction\JobDataActionController::seedData');
            $routes->get('list', 'AppSetup\JobDataAction\JobDataActionController::generateList');
        });


        // Route untuk job data reason
        $routes->group('/job_data_reason', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\JobDataReason\JobDataReasonController::index');
            $routes->get('get_by_action/(:any)', 'AppSetup\JobDataReason\JobDataReasonController::getByAction/$1');
            $routes->post('save', 'AppSetup\JobDataReason\JobDataReasonController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\JobDataReason\JobDataReasonController::loadTable', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\JobDataReason\JobDataReasonController::get/$1');
            $routes->post('update', 'AppSetup\JobDataReason\JobDataReasonController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\JobDataReason\JobDataReasonController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\JobDataReason\JobDataReasonController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\JobDataReason\JobDataReasonController::seedData');
            $routes->get('list', 'AppSetup\JobDataReason\JobDataReasonController::generateList');
        });

        $routes->group('/job_data', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\JobData\JobDataController::index');
            $routes->get('info/(:any)', 'AppSetup\JobData\JobDataController::getJobDataInfo/$1');
            $routes->post('table', 'AppSetup\JobData\JobDataController::loadTable');
            $routes->get('register', 'AppSetup\JobData\JobDataController::registerJobData');
            $routes->post('mass-delete', 'AppSetup\JobData\JobDataController::massDelete');
            $routes->get('export', 'AppSetup\JobData\JobDataController::export');
            $routes->get('get/(:any)', 'AppSetup\JobData\JobDataController::get/$1');
            $routes->get('show/(:any)', 'AppSetup\JobData\JobDataController::show/$1');
            $routes->post('update', 'AppSetup\JobData\JobDataController::update');
            $routes->post('delete', 'AppSetup\JobData\JobDataController::delete');
        });

        // Route untuk APQP Setup
        $routes->group('/apqp_setup', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\ApqpSetup\ApqpHeaderController::index');
            $routes->post('table', 'AppSetup\ApqpSetup\ApqpHeaderController::loadTable');
            $routes->post('save', 'AppSetup\ApqpSetup\ApqpHeaderController::save');
            $routes->get('get/(:any)', 'AppSetup\ApqpSetup\ApqpHeaderController::get/$1');
            $routes->post('update', 'AppSetup\ApqpSetup\ApqpHeaderController::update');
            $routes->post('delete', 'AppSetup\ApqpSetup\ApqpHeaderController::delete');
            $routes->get('document_list/(:any)', 'AppSetup\ApqpSetup\ApqpHeaderController::documentList/$1');
            $routes->post('save_document', 'AppSetup\ApqpSetup\ApqpHeaderController::saveDocument');
            $routes->post('update_document', 'AppSetup\ApqpSetup\ApqpHeaderController::updateDocument');
            $routes->post('delete_document', 'AppSetup\ApqpSetup\ApqpHeaderController::deleteDocument');
            $routes->get('get_approver/(:any)', 'AppSetup\ApqpSetup\ApqpHeaderController::getApprover/$1');
            $routes->post('save_approver', 'AppSetup\ApqpSetup\ApqpHeaderController::saveApprover');
            $routes->post('update_approver', 'AppSetup\ApqpSetup\ApqpHeaderController::updateApprover');
        });

        // Route untuk document flow
        $routes->group('/document_flow', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {});

        // Route buat project setup
        $routes->group('/project_setup', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', '');
        });

        // Routes untuk period setup
        $routes->group('/period_setup', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\PeriodSetup\PeriodController::index');
            $routes->post('save', 'AppSetup\PeriodSetup\PeriodController::saveDefault', ['filter' => 'ratelimit:3,60']);
        });

        // Routes untuk status absensi
        $routes->group('/absence_status', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\AbsenceStatus\AbsenceStatusController::index');
            $routes->post('save', 'AppSetup\AbsenceStatus\AbsenceStatusController::save');
            $routes->post('table', 'AppSetup\AbsenceStatus\AbsenceStatusController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\AbsenceStatus\AbsenceStatusController::get/$1');
            $routes->post('update', 'AppSetup\AbsenceStatus\AbsenceStatusController::update');
            $routes->post('delete', 'AppSetup\AbsenceStatus\AbsenceStatusController::delete');
            $routes->get('export', 'AppSetup\AbsenceStatus\AbsenceStatusController::export');
            $routes->get('seed', 'AppSetup\AbsenceStatus\AbsenceStatusController::seedData');
        });

        // Routes untuk overtime setup
        $routes->group('/overtime_setup', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\OvertimeSetup\OvertimeSetupController::index');
            $routes->post('save', 'AppSetup\OvertimeSetup\OvertimeSetupController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\OvertimeSetup\OvertimeSetupController::loadTable');
            $routes->get('get_rate/(:any)', 'AppSetup\OvertimeSetup\OvertimeSetupController::getRate/$1');
            $routes->get('get/(:any)', 'AppSetup\OvertimeSetup\OvertimeSetupController::get/$1');
            $routes->post('update_rate', 'AppSetup\OvertimeSetup\OvertimeSetupController::updateRate', ['filter' => 'ratelimit:3,60']);
            $routes->post('update', 'AppSetup\OvertimeSetup\OvertimeSetupController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\OvertimeSetup\OvertimeSetupController::delete');
            $routes->get('export', 'AppSetup\OvertimeSetup\OvertimeSetupController::export');
            $routes->get('seed', 'AppSetup\OvertimeSetup\OvertimeSetupController::seedData');
        });

        // Routes untuk shift setup
        $routes->group('/shift_setup', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\ShiftSetup\ShiftController::index');
            $routes->get('add', 'AppSetup\ShiftSetup\ShiftController::add');
            $routes->post('hitung_lembur', 'AppSetup\ShiftSetup\ShiftController::calculateOvertime', ['filter' => 'ratelimit:3,60']);
            $routes->post('save', 'AppSetup\ShiftSetup\ShiftController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\ShiftSetup\ShiftController::loadTable');
            $routes->get('get/(:any)', 'AppSetup\ShiftSetup\ShiftController::get/$1');
            $routes->get('show/(:any)', 'AppSetup\ShiftSetup\ShiftController::show/$1');
            $routes->post('update', 'AppSetup\ShiftSetup\ShiftController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\ShiftSetup\ShiftController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->post('mass-delete', 'AppSetup\ShiftSetup\ShiftController::deleteAll', ['filter' => 'ratelimit:3,60']);
            $routes->post('prev', 'AppSetup\ShiftSetup\ShiftController::prev', ['filter' => 'ratelimit:3,60']);
            $routes->post('next', 'AppSetup\ShiftSetup\ShiftController::next', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\ShiftSetup\ShiftController::export', ['filter' => 'ratelimit:3,60']);
        });

        // Routes untuk special leave
        $routes->group('special_leave_setup', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\SpecialLeave\SpecialLeaveController::index');
            $routes->post('save', 'AppSetup\SpecialLeave\SpecialLeaveController::save', ['filter' => 'ratelimit:3,60']);
            $routes->get('get/(:any)', 'AppSetup\SpecialLeave\SpecialLeaveController::get/$1');
            $routes->post('update', 'AppSetup\SpecialLeave\SpecialLeaveController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\SpecialLeave\SpecialLeaveController::loadTable');
            $routes->post('delete', 'AppSetup\SpecialLeave\SpecialLeaveController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\SpecialLeave\SpecialLeaveController::export', ['filter' => 'ratelimit:3,60']);
            $routes->get('seed', 'AppSetup\SpecialLeave\SpecialLeaveController::seedData');
        });

        // Routes untuk schedulle setup
        $routes->group('schedulle_setup', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\SchedulleSetup\SchedulleController::index');
            $routes->get('add', 'AppSetup\SchedulleSetup\SchedulleController::add');
            $routes->post('save', 'AppSetup\SchedulleSetup\SchedulleController::save', ['filter' => 'ratelimit:3,60']);
            $routes->post('table', 'AppSetup\SchedulleSetup\SchedulleController::loadTable');
            $routes->get('shift/(:any)', 'AppSetup\SchedulleSetup\SchedulleController::shift/$1');
            $routes->get('get/(:any)', 'AppSetup\SchedulleSetup\SchedulleController::get/$1');
            $routes->get('show/(:any)', 'AppSetup\SchedulleSetup\SchedulleController::show/$1');
            $routes->post('update', 'AppSetup\SchedulleSetup\SchedulleController::update', ['filter' => 'ratelimit:3,60']);
            $routes->post('delete', 'AppSetup\SchedulleSetup\SchedulleController::delete', ['filter' => 'ratelimit:3,60']);
            $routes->post('prev', 'AppSetup\SchedulleSetup\SchedulleController::prev', ['filter' => 'ratelimit:3,60']);
            $routes->post('next', 'AppSetup\SchedulleSetup\SchedulleController::next', ['filter' => 'ratelimit:3,60']);
            $routes->post('mass-delete', 'AppSetup\SchedulleSetup\SchedulleController::deleteAll', ['filter' => 'ratelimit:3,60']);
            $routes->get('export', 'AppSetup\SchedulleSetup\SchedulleController::export');
        });

        // Routes untuk document flow
        $routes->group('/document_flow', ['filter' => ['role:superadmin,administrator,admin']], static function ($routes) {
            $routes->get('', 'AppSetup\DocumentFlow\DocumentFlowController::index');
            $routes->post('save', 'AppSetup\DocumentFlow\DocumentFlowController::save', ['filter' => 'ratelimit:3,60']);
        });

        // Site Setting
        $routes->get('/site-setting', 'SiteSetting\SiteSettingController::index', ['filter' => ['role:superadmin,admin', 'ratelimit:100,60']]);
    });
