<?php

namespace App\Controllers\AppSetup\MaterialCategory;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\MaterialCategory\MaterialCategoryService;
use App\Repositories\MaterialCategory\MaterialCategoryRepository;
use App\Traits\ResponseTrait;

class MaterialCategoryController extends BaseController
{
    use ResponseTrait;
    protected $category;

    public function __construct()
    {
        $this->category = new MaterialCategoryService(new MaterialCategoryRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();

                $output = $this->category->loadTable($requestData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialCategoryController::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => "Material Category Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/MaterialCategory/material_category.js' . '"></script>'
            ]
        ];

        return view('AppSetup/MaterialCategory/index', $data);
    }

    function save()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[MaterialCategoryController::saveData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();
            $save = $this->category->saveData($data);
            if ($save) {
                return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully", $save);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MaterialCategoryController::saveData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function get($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[MaterialCategoryController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->category->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MaterialCategoryController::getData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function update()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[MaterialCategoryController::updateData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->category->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MaterialCategoryController::updateData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[MaterialCategoryController::deleteData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Token is required", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }


            if ($this->category->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MaterialCategoryController::deleteData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function export()
    {
        try {
            $result = $this->category->exportData();

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
            log_message('error', "[MaterialCategoryController::exportData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function seedData()
    {
        return $this->response->setJSON($this->category->loadAllData(), JSON_PRETTY_PRINT);
    }
}
