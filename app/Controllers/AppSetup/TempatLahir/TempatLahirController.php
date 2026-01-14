<?php

namespace App\Controllers\AppSetup\TempatLahir;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\TempatLahirService;
use App\Repositories\TempatLahirRepository;
use App\Traits\ResponseTrait;

class TempatLahirController extends BaseController
{
    use ResponseTrait;
    protected $tempatLahirService;

    public function __construct()
    {
        $this->tempatLahirService = new TempatLahirService(new TempatLahirRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Birth Place Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/TempatLahir/tempat_lahir.js' . '"></script>'
            ]
        ];

        return view('AppSetup/TempatLahir/index', $data);
    }

    function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();

                $output = $this->tempatLahirService->loadTable($requestData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[TempatLahirController::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function save()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[TempatLahirController::saveData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->tempatLahirService->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TempatLahirController::saveData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function get($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[TempatLahirController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->tempatLahirService->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TempatLahirController::getData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function update()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[TempatLahirController::updateData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->tempatLahirService->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TempatLahirController::updateData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[TempatLahirController::deleteData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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

            if ($this->tempatLahirService->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[TempatLahirController::deleteData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function export()
    {
        try {
            $result = $this->tempatLahirService->exportData();

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
            log_message('error', "[TempatLahirController::exportData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    function seedData()
    {
        return $this->response->setJSON($this->tempatLahirService->loadAllData(), JSON_PRETTY_PRINT);
    }
}
