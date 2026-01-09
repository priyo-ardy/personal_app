<?php

namespace App\Controllers\AppSetup\Position;

use App\Controllers\BaseController;
use App\Repositories\NbhxPositionRepository;
use App\Repositories\PositionRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\DataTableRepository;
use App\Repositories\EmployeeGradeRepository;
use App\Repositories\EmployeeRankRepository;
use App\Repositories\EmployeeCategoryRepository;
use App\Repositories\ClassNbhxRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\NbhxPositionService;
use App\Services\DepartmentService;
use App\Services\EmployeeGradeService;
use App\Services\EmployeeRankService;
use App\Services\EmployeeCategoryService;
use App\Services\ClassNbhxService;
use App\Services\PositionService;
use App\Traits\ResponseTrait;

class PositionController extends BaseController
{
    use ResponseTrait;
    protected $nbhxPositionService;
    protected $departmentService;
    protected $gradeService;
    protected $rankService;
    protected $categoryService;
    protected $classService;
    protected $positionService;

    public function __construct()
    {
        $this->nbhxPositionService = new NbhxPositionService(new NbhxPositionRepository());
        $this->departmentService = new DepartmentService(new DepartmentRepository());
        $this->gradeService = new EmployeeGradeService(new EmployeeGradeRepository());
        $this->rankService = new EmployeeRankService(new EmployeeRankRepository());
        $this->categoryService = new EmployeeCategoryService(new EmployeeCategoryRepository());
        $this->classService = new ClassNbhxService(new ClassNbhxRepository());
        $this->positionService = new PositionService(new PositionRepository());
    }
    public function index()
    {
        $data = [
            'title' => "Employee Position Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Position/position.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Position/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Add New Position",
            'nbhx_position' => $this->nbhxPositionService->loadData(),
            'dept' => $this->departmentService->loadData(),
            'grade' => $this->gradeService->loadData(),
            'rank' => $this->rankService->loadData(),
            'emp_category' => $this->categoryService->loadData(),
            'class_nbhx' => $this->classService->loadData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Position/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Position/add', $data);
    }

    public function dataList()
    {
        try {
            $generate_data = $this->positionService->loadData();

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $generate_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::dataList] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }
}
