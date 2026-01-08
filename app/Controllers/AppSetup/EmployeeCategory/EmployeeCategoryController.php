<?php

namespace App\Controllers\AppSetup\EmployeeCategory;

use App\Controllers\BaseController;
use App\Repositories\EmployeeCategoryRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\EmployeeCategoryService;
use App\Traits\ResponseTrait;
use SebastianBergmann\CodeCoverage\Test\TestStatus\Success;

class EmployeeCategoryController extends BaseController
{
    use ResponseTrait;
    protected $categoryService;

    public function __construct()
    {
        $this->categoryService = new EmployeeCategoryService(new EmployeeCategoryRepository());
    }
    public function index()
    {
        $data = [
            'title' => "Employee Category Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/EmployeeCategory/category.js' . '"></script>'
            ]
        ];

        return view('AppSetup/EmployeeCategory/index', $data);
    }

    function loadTable()
    {
        if ($this->request->isAJAX()) {
            $requestData = $this->request->getPost();

            $output = $this->categoryService->loadTable($requestData);

            return $this->response->setJSON($output);
        }

        return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
    }

    function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[EmployeeCategoryController::saveData] Request method not allowed by {NIK}, from {ip}", [
                'NIK' => session()->get('user_name'),
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);

            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->categoryService->save($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "New employee category data saved successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function getData($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[EmployeeCategoryController::getData] Request method not allowed by {NIK}, from {ip}", [
                'NIK' => session()->get('user_name'),
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);

            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->categoryService->getData($id);
            if ($get_data instanceof \Exception) {
                throw $get_data;
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $get_data);

            return $this->success(ResponseInterface::HTTP_OK, "Employee category data retrieved successfully", $get_data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function updateData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[EmployeeCategoryController::updateData] Request method not allowed by {NIK}, from {ip}", [
                'NIK' => session()->get('user_name'),
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);

            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->categoryService->update($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Employee category data updated successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function deleteData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[EmployeeCategoryController::deletData] Request method not allowed by {NIK}, from {ip}", [
                'NIK' => session()->get('user_name'),
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);

            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Token is required", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            if ($this->categoryService->delete($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Employee category data deleted successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function exportData()
    {
        try {
            $result = $this->categoryService->export();

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
            log_message('error', '[EmployeeCategoryController::exportData], Unexpected error occurred NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        return $this->response->setJSON($this->categoryService->loadData(), JSON_PRETTY_PRINT);
    }
}
