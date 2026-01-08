<?php

namespace App\Controllers\AppSetup\ClassNBHX;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\ClassNbhxService;
use App\Repositories\ClassNbhxRepository;
use App\Traits\ResponseTrait;

class ClassNbhxController extends BaseController
{
    use ResponseTrait;
    protected $classService;

    public function __construct()
    {
        $this->classService = new ClassNbhxService(new ClassNbhxRepository());
    }

    public function index()
    {
        $data = [
            'title' => 'Employee Class NBHX Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/ClassNbhx/class_nbhx.js' . '"></script>'
            ]
        ];

        return view('AppSetup/ClassNbhx/index', $data);
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->classService->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message(
                'error',
                '[ClassNbhxController::save] Request method not allowed for user {NIK} from {ip}',
                ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]
            );

            throw new \Exception("Request not alowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->classService->save($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "New employee class nbhx data saved successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[ClassNbhxController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->classService->getData($id);
            if ($get_data instanceof \Exception) {
                throw $get_data;
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $get_data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[ClassNbhxController::update] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->classService->update($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Employee class nbhx data updated successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[ClassNbhxController::delete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
            $id_class = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_class[] = dekripsi($token[$i]);
            }

            if ($this->classService->delete($id_class)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function export()
    {
        try {
            $result = $this->classService->export();

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
            return $this->exceptionResponse($e);
        }
    }

    public function  seedData()
    {
        return $this->response->setJSON($this->classService->loadData(), JSON_PRETTY_PRINT);
    }
}
