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
use App\Repositories\JobData\JobDataRepository;
use App\Services\JobData\JobDataService;
use App\Services\JobDataReason\JobDataReasonService;
use App\Repositories\JobDataReason\JobDataReasonRepository;

class JobDataController extends BaseController
{
    protected $action;
    protected $position;
    protected $employee;
    protected $job_data;
    protected $reason;

    public function __construct()
    {
        $this->action = new JobDataActionService(new JobDataActionRepository());
        $this->position = new PositionService(new PositionRepository());
        $this->employee = new EmployeeService(new EmployeeRepository());
        $this->job_data = new JobDataService(new JobDataRepository());
        $this->reason = new JobDataReasonService(new JobDataReasonRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Latest Job Data",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/MasterData/JobData/job_data.js' . '"></script>',
            ]
        ];

        return view('MasterData/JobData/index', $data);
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $postData = $this->request->getPost();

                $output = $this->job_data->loadTable($postData);

                return $this->response->setJSON($output, JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function registerJobData()
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

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[JobDataController::save] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->job_data->saveData($postData)) {
                return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
