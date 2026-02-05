<?php

namespace App\Controllers\MasterData\Employee;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Employee\EmployeeService;
use App\Repositories\Employee\EmployeeRepository;
use App\Services\JobDataAction\JobDataActionService;
use App\Repositories\JobDataAction\JobDataActionRepository;
use App\Services\PositionService;
use App\Repositories\PositionRepository;
use App\Traits\KalkulasiTrait;

class EmployeeJobDataController extends BaseController
{
    protected $employee;
    protected $action;
    protected $position;

    public function __construct()
    {
        $this->employee = new EmployeeService(new EmployeeRepository());
        $this->action = new JobDataActionService(new JobDataActionRepository());
        $this->position = new PositionService(new PositionRepository());
    }
    public function index()
    {
        //
    }

    public function add(string $token)
    {
        $employee_id = dekripsi($token);
        $employee = $this->employee->getData($employee_id);

        $data = [
            'title' => "Register New Employee Job Data",
            'token' => $token,
            'karyawan' => $employee,
            'action' => $this->action->getAllData(),
            'position' => $this->position->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/MasterData/Employee/add_job_data.js' . '"></script>'
            ]
        ];

        return view('MasterData/Employee/job_data.php', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[EmployeeJobDataController::save] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EmployeeJobDataController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
