<?php

namespace App\Controllers\AppSetup\Workshop;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Workshop\WorkshopService;
use App\Repositories\Workshop\WorkshopRepository;
use App\Traits\ResponseTrait;

class WorkshopController extends BaseController
{
    use ResponseTrait;
    protected $workshopService;

    public function __construct()
    {
        $this->workshopService = new WorkshopService(new WorkshopRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->workshopService->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[WorkshopController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => "Workshop Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Workshop/workshop.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Workshop/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[WorkshopController::save] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->workshopService->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[WorkshopController::save] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[WorkshopController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $getData = $this->workshopService->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $getData);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[WorkshopController::getData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[WorkshopController::updateData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->workshopService->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[WorkshopController::updateData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[WorkshopController::deleteData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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

            if ($this->workshopService->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[WorkshopController::deleteData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->workshopService->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \RuntimeException('Unexpected response from export service');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[WorkshopController::export] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        try {
            return $this->success(ResponseInterface::HTTP_OK, "Data seeded successfully", $this->workshopService->generateList());
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[WorkshopController::seedData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
