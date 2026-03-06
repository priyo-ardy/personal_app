<?php

namespace App\Controllers\AppSetup\AbsenceStatus;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\AbsenceStatus\AbsenceStatusService;
use App\Repositories\AbsenceStatus\AbsenceStatusRepository;

class AbsenceStatusController extends BaseController
{
    protected $absence;

    public function __construct()
    {
        $this->absence = new AbsenceStatusService(new AbsenceStatusRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Absence Status Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/AbsenceStatus/absence_status.js' . '"></script>'
            ]
        ];

        return view('AppSetup/AbsenceStatus/index', $data);
    }

    public function loadTable()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', '[AbsenceStatusController::loadTable] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            if ($this->request->isAJAX()) {
                $postData = $this->request->getPost();

                $output = $this->absence->loadTable($postData);

                return $this->response->setJSON($output, JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[AbsenceStatusController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function save()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', '[AbsenceStatusController::save] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->absence->saveData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[AbsenceStatusController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get(string $token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', '[AbsenceStatusController::get] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $getData = $this->absence->getData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $getData);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[AbsenceStatusController::get] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', '[AbsenceStatusController::update] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $this->absence->updateData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data updated successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[AbsenceStatusController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', '[AbsenceStatusController::delete] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
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

            foreach ($json_data['token'] as $key => $value) {
                $id[] = dekripsi($value);
            }

            $this->absence->deleteData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[AbsenceStatusController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->absence->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Failed to generate file", ResponseInterface::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[AbsenceStatusController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        try {
            $data = $this->absence->getAllData();

            return pesan(ResponseInterface::HTTP_OK, "Data seeded successfully", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[AbsenceStatusController::seedData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
