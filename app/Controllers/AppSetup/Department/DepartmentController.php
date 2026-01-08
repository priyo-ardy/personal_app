<?php

namespace App\Controllers\AppSetup\Department;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\DepartmentService;
use App\Repositories\DepartmentRepository;
use CodeIgniter\HTTP\Response;
use SebastianBergmann\CodeCoverage\Test\TestStatus\Success;

class DepartmentController extends BaseController
{
    protected $deptService;

    public function __construct()
    {
        $this->deptService = new DepartmentService(new DepartmentRepository());
    }
    public function index()
    {
        $data = [
            'title' => "Department Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Dept/dept.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Dept/index.php', $data);
    }

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[DepartmentController::saveData] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->deptService->save($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'Data saved successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DepartmentController::saveData], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function loadTable()
    {
        if ($this->request->isAJAX()) {
            $requestData = $this->request->getPost();

            $output = $this->deptService->loadTable($requestData);

            return $this->response->setJSON($output);
        }

        return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
    }

    function getData($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[DepartmentController::getData] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id_dept = dekripsi($token);
            $get_data = $this->deptService->getUserData($id_dept);
            if ($get_data) {
                return pesan(ResponseInterface::HTTP_OK, 'Dept data found', $get_data);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DepartmentController::getData], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function updatedata()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[DepartmentController:updateData] Invalid request method, NIK {NIK}, from IP {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->deptService->update($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'Data updated successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DepartmentController::updateData] Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function massDelete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[DepartmentController::massDelete] Invalid request method, NIK {NIK}, from IP {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Token is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id_dept = $json_data['token'];
            $dept_data = [];

            for ($i = 0; $i < count($id_dept); $i++) {
                $dept_data[] = dekripsi($id_dept[$i]);
            }

            if ($this->deptService->massDelete($dept_data)) {
                return pesan(ResponseInterface::HTTP_OK, 'Data deleted successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DepartmentController::massdelete] Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function exportData()
    {
        try {
            $result = $this->deptService->exportData();

            // If the result is an exception, throw it
            if ($result instanceof \Exception) {
                throw $result;
            }

            // If the result is a ResponseInterface, return it directly
            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            // If we get here, something unexpected happened
            throw new \RuntimeException('Unexpected response from export service');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DepartmentController::exportData], Unexpected error occurred NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        $data = $this->deptService->loadData();

        return $this->response->setJSON($data, JSON_PRETTY_PRINT);
    }
}
