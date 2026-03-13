<?php

namespace App\Controllers\AppSetup\SpecialLeave;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\SpecialLeave\SpecialLeaveService;
use App\Repositories\SpecialLeave\SpecialLeaveRepository;

class SpecialLeaveController extends BaseController
{
    protected $leave;

    public function __construct()
    {
        $this->leave = new SpecialLeaveService(new SpecialLeaveRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $postData = $this->request->getPost();

                $output = $this->leave->loadTable($postData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SpecialLeaveController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Special Leave Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SpecialLeave/special_leave.js' . '"></script>'
            ]
        ];

        return view('AppSetup/SpecialLeave/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SpecialLeaveController::save] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->leave->saveData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SpecialLeaveController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get(string $token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[SpecialLeaveController::get] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get = $this->leave->getData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SpecialLeaveController::get] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SpecialLeaveController::update] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->leave->updateData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data updated successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SpecialLeaveController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SpecialLeaveController::delete] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
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

            $this->leave->deleteData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SpecialLeaveController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->leave->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SpecialLeaveController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
