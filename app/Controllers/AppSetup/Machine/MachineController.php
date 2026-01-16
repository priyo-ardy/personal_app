<?php

namespace App\Controllers\AppSetup\Machine;

use App\Controllers\BaseController;
use App\Models\AppSetup\Machine\MachineModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Repositories\Machine\MachineRepository;
use App\Services\Machine\MachineService;
use App\Repositories\Workshop\WorkshopRepository;
use App\Services\Workshop\WorkshopService;
use App\Repositories\Tonnage\TonnageRepository;
use App\Services\Tonnage\TonnageService;
use App\Traits\ResponseTrait;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class MachineController extends BaseController
{
    use ResponseTrait;
    protected $machine;
    protected $workshop;
    protected $tonnage;

    public function __construct()
    {
        $this->machine = new MachineService(new MachineRepository());
        $this->workshop = new WorkshopService(new WorkshopRepository());
        $this->tonnage = new TonnageService(new TonnageRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->machine->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MachineController::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => "Machine Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Machine/machine.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Machine/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Add New Machine",
            'workshop' => $this->workshop->generateList(),
            'tonnage' => $this->tonnage->loadAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Machine/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Machine/add', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MachineController::save] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->machine->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::save] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[MachineController::get] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get_data = $this->machine->getData(dekripsi($token));

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $get_data['token']);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::get] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function show($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[MachineController::show] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);
            $row = $this->machine->getData($id);
            if (!$row) {
                return PageNotFoundException::forPageNotFound();
            }

            $data = [
                'title' => "Edit Machine Data",
                'data' => $row,
                'workshop' => $this->workshop->generateList(),
                'tonnage' => $this->tonnage->loadAllData(),
                'footer' => [
                    '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                    '<script src="' . base_url() . 'js/AppSetup/Machine/edit.js' . '"></script>'
                ]
            ];

            return view('AppSetup/Machine/edit', $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::show] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MachineController::update] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->machine->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::update] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MachineController::delete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = trim($json_data['token']);
            $id = dekripsi($token);

            if ($this->machine->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::delete] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function prev()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MachineController::prev] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Code is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['workshop'])) {
                throw new \Exception("Workshop is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = trim($json_data['code']);
            $workshop = trim($json_data['workshop']);

            $prev = $this->machine->prevData($code, $workshop);
            if (!$prev) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $prev);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::prev] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function next()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MachineController::next] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Code is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['workshop'])) {
                throw new \Exception("Workshop is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = trim($json_data['code']);
            $workshop = trim($json_data['workshop']);

            $next = $this->machine->nextData($code, $workshop);
            if (!$next) {
                throw new \Exception("You are in the last data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $next);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::next] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[MachineController::export] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $result = $this->machine->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Failed to export data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::export] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function massDelete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MachineController::massDelete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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

            $token = $json_data['token'];
            $id = [];
            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            if ($this->machine->massDeleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data deleted successfully");
            }

            throw new \Exception("Failed to delete data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::massDelete] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[MachineController::seedData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return PageNotFoundException::forPageNotFound();
        }

        try {
            $load_data = $this->machine->loadAllData();
            if (!$load_data) {
                throw new \Exception("Failed to load data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->success(ResponseInterface::HTTP_OK, "Load data success", $load_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[MachineController::seedData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
