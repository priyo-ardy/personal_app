<?php

namespace App\Controllers\AppSetup\Customer;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\CustomerCategory\CustomerCategoryService;
use App\Repositories\CustomerCategory\CustomerCategoryRepository;
use App\Services\Customer\CustomerService;
use App\Repositories\Customer\CustomerRepository;

class CustomerController extends BaseController
{
    protected $customer;
    protected $category;

    public function __construct()
    {
        $this->category = new CustomerCategoryService(new CustomerCategoryRepository());
        $this->customer = new CustomerService(new CustomerRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Customer Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Customer/customer.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Customer/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Customer Management",
            'category' => $this->category->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Customer/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Customer/add', $data);
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();
                $output = $this->customer->loadTable($requestedData);

                return $this->response->setJSON($output);
            }

            throw new \Exception("Request not allowed", ResponseInterface::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[CustomerController::save] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $postData = $this->request->getPost();
            $this->customer->saveData($postData);
            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[CustomerController::get] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $id = dekripsi($token);
            $data = $this->customer->getData($id);
            $token = $data['token'];
            return pesan(ResponseInterface::HTTP_OK, "Data found", $token);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::get] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function show($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[CustomerController::show] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $id = dekripsi($token);
            $customer = $this->customer->getData($id);

            $data = [
                'title' => "Customer Details | " . $customer['name'],
                'customer' => $customer,
                'category' => $this->category->getAllData(),
                'footer' => [
                    '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                    '<script src="' . base_url() . 'js/AppSetup/Customer/show.js' . '"></script>'
                ]
            ];

            return view('AppSetup/Customer/show', $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::show] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function  update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[CustomerController::update] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $postData = $this->request->getPost();
            $this->customer->updateData($postData);
            return pesan(ResponseInterface::HTTP_OK, "Data updated successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[CustomerController::delete] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Invalid data format", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($json_data['token']);

            $this->customer->deleteData($id);
            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function massDelete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[CustomerController::massDelete] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Invalid data format", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            $this->customer->massDelete($id);
            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::massDelete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[CustomerController::export] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $result = $this->customer->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Failed to export data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[CustomerController::seedData] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $data = $this->customer->getAllData();
            return pesan(ResponseInterface::HTTP_OK, "Data seeded successfully", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::seedData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function prev()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[CustomerController::prev] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Invalid data format", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = $json_data['code'];
            $data = $this->customer->prevData($code);
            return pesan(ResponseInterface::HTTP_OK, "Data found", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::prev] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function next()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[CustomerController::next] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Invalid data format", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = $json_data['code'];
            $data = $this->customer->nextData($code);
            return pesan(ResponseInterface::HTTP_OK, "Data found", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CustomerController::next] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
