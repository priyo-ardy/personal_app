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
            // 'employee' => $this->employee->unRegisteredJobData(),
            'action' => $this->action->getAllData(),
            'position' => $this->position->getAllData(),
            'superior' => $this->employee->listEmployeeByDate(date('Y-m-d')),
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

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[JobDataController::get] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get_data = $this->job_data->getData(dekripsi($token));

            return pesan(ResponseInterface::HTTP_OK, "Data found", $get_data['token']);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataController::get] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function show($token)
    {
        $info = $this->job_data->getData(dekripsi($token));
        $employee_list = $this->employee->getData($info['employee_token']);
        $position_info = $this->position->getPositionData($info['position']);

        $data = [
            'title' => 'Change Job Data | ' . "$employee_list->nik - $employee_list->name",
            'data' => $info,
            'employee' => $employee_list,
            'action' => $this->action->getAllData(),
            'reason' => $this->reason->getAllData(),
            'position' => $this->position->getAllData(),
            'superior' => $this->employee->listEmployeeByDate(date('Y-m-d')),
            'position_info' => $position_info,
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/MasterData/JobData/show.js' . '"></script>'
            ]
        ];

        return view('MasterData/JobData/show', $data);
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[JobDataController::update] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->job_data->updateData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data updated successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[JobDataController::delete] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($json_data['token']));

            $this->job_data->deleteData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function massDelete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[JobDataController::delete] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            $this->job_data->deleteMassData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[JobDataController::export] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $result = $this->job_data->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            return $result;
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
