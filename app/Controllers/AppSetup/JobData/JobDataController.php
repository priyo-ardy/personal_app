<?php

namespace App\Controllers\AppSetup\JobData;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\JobDataAction\JobDataActionService;
use App\Repositories\JobDataAction\JobDataActionRepository;
use App\Services\PositionService;
use App\Repositories\PositionRepository;
use App\Services\Employee\EmployeeService;
use App\Repositories\Employee\EmployeeRepository;

class JobDataController extends BaseController
{
    protected $action;
    protected $position;
    protected $employee;

    public function __construct()
    {
        $this->action = new JobDataActionService(new JobDataActionRepository());
        $this->position = new PositionService(new PositionRepository());
        $this->employee = new EmployeeService(new EmployeeRepository());
    }

    public function index()
    {
        //
    }

    public function register()
    {
        $data = [
            'title' => "Register Employee Job Data",
            'employee' => $this->employee->unRegisteredJobData(),
            'action' => $this->action->getAllData(),
            'position' => $this->position->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/MasterData/JobData/register_job_data.js' . '"></script>'
            ]
        ];

        return view('MasterData/JobData/register_job_data', $data);
    }
}
