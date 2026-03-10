<?php

namespace App\Controllers\AppSetup\OvertimeSetup;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\OvertimeSetup\OvertimeSetupService;
use App\Repositories\OvertimeSetup\OvertimeSetupRepository;

class OvertimeSetupController extends BaseController
{
    protected $overtime;

    public function __construct()
    {
        $this->overtime = new OvertimeSetupService(new OvertimeSetupRepository());
    }

    public function loadTable()
    {
        if ($this->request->isAJAX()) {
            $postData = $this->request->getPost();

            $output = $this->overtime->loadTable($postData);

            return $this->response->setJSON($output);
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Overtime Setup',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Overtimesetup/overtime_setup.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Overtimesetup/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', '[OvertimeSetupController::save] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception('request method not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->overtime->saveData($postData);

            return pesan(ResponseInterface::HTTP_OK, 'New overtime setup created successfully');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[OvertimeSetupController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function getRate($token)
    {
        try {
            $id = dekripsi($token);

            $get_rate = $this->overtime->getRateList($id);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $get_rate);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[OvertimeSetupController::getRate] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function updateRate()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[OvertimeSetupController::updateRate] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->overtime->updateOvertimeRate($postData);

            return pesan(ResponseInterface::HTTP_OK, 'Overtime rate updated successfully');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[OvertimeSetupController::updateRate] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get(string $token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[OvertimeSetupController::get] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get = $this->overtime->getData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[OvertimeSetupController::get] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[OvertimeSetupController::update] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->overtime->updateData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data updated successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[OvertimeSetupController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[OvertimeSetupController::delete] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
            $id  = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            $this->overtime->deleteData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[OvertimeSetupController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->overtime->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Unexpected error occured", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[OvertimeSetupController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
