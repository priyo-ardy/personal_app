<?php

namespace App\Controllers\AppSetup\EquipmentType;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Repositories\EquipmentType\EquipmentTypeRepository;
use App\Services\EquipmentType\EquipmentTypeService;
use App\Traits\ResponseTrait;
use Throwable;

class EquipmentTypeController extends BaseController
{
    use ResponseTrait;
    protected $equipment;

    public function __construct()
    {
        $this->equipment = new EquipmentTypeService(new EquipmentTypeRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->equipment->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EquipmentTypeController::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => "Equipment Type Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/EquipmentType/equipment_type.js' . '"></script>'
            ]
        ];

        return view('AppSetup/EquipmentType/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[EquipmentTypeController::save] Request method not allowed by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->equipment->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "New equipment data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EquipmentTypeController::save] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get(string $token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[EquipmentTypeController::get] Request method not allowed by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $getData = $this->equipment->getData($id);

            if ($getData instanceof \Exception) {
                throw $getData;
            }

            return $this->success(ResponseInterface::HTTP_OK, "Retrive data success", $getData);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EquipmentTypeController::get] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[EquipmentTypeController::update] Request method not allowed by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->equipment->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Equipment type data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EquipmentTypeController::update] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[EquipmentTypeController::delete] Request method not allowed by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request data is not a valid JSON", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Equipment type token is not available", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            if ($this->equipment->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Equipment data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EquipmentTypeController::delete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[EquipmentTypeController::export] Request method not allowed by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $result = $this->equipment->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception('Unexpected response from export service');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EquipmentTypeController::export] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[EquipmentTypeController::seedData] Request method not allowed by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->equipment->loadAllData();
            if ($data instanceof \Exception) {
                throw $data;
            }

            return $this->success(ResponseInterface::HTTP_OK, "Load data success", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EquipmentTypeController::seedData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
