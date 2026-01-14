<?php

namespace App\Controllers\AppSetup\Tonnage;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Tonnage\TonnageService;
use App\Repositories\Tonnage\TonnageRepository;
use App\Traits\ResponseTrait;

class TonnageController extends BaseController
{
    use ResponseTrait;
    protected $tonnage;

    public function __construct()
    {
        $this->tonnage = new TonnageService(new TonnageRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();

                $output = $this->tonnage->loadTable($requestData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[TonnageController::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => "Injection Machine Tonnage Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Tonnage/tonnage.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Tonnage/index', $data);
    }

    function save()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[TonnageController::saveData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->tonnage->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TonnageController::saveData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function get($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[TonnageController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->tonnage->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TonnageController::getData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function update()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[TonnageController::updateData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->tonnage->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TonnageController::updateData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[TonnageController::deleteData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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

            if ($this->tonnage->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TonnageController::deleteData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function export()
    {
        try {
            $result = $this->tonnage->exportData();

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
            log_message('error', "[TonnageController::exportData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function seedData()
    {
        return $this->response->setJSON($this->tonnage->loadAllData(), JSON_PRETTY_PRINT);
    }
}
