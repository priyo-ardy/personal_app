<?php

namespace App\Controllers\AppSetup\GroupLeader;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\GroupLeader\GroupLeaderService;
use App\Repositories\GroupLeader\GroupLeaderRepository;
use App\Services\TeamLeader\TeamLeaderService;
use App\Repositories\TeamLeader\TeamLeaderRepository;

class GroupLeaderController extends BaseController
{
    protected $group_leader;
    protected $team_leader;

    public function __construct()
    {
        $this->group_leader = new GroupLeaderService(new GroupLeaderRepository());
        $this->team_leader = new TeamLeaderService(new TeamLeaderRepository());
    }

    public function  loadTable()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[GroupLeaderController::loadTable] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            if ($this->request->isAJAX()) {
                $requstedData = $this->request->getPost();
                $output = $this->group_leader->loadTable($requstedData);
                return $this->response->setJSON($output);
            }

            log_message('error', '[GroupLeaderController::loadTable] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[GroupLeaderController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
    public function index()
    {
        $data = [
            'title' => 'Group Leader Management',
            'employee' => $this->team_leader->generateEmployeeList(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/GroupLeader/group_leader.js' . '"></script>',
            ]
        ];


        return view('AppSetup/GroupLeader/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[GroupLeaderController::save] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->group_leader->saveData($postData)) {
                return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[GroupLeaderController::save] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get(string $token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[GroupLeaderController::get] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);
            $getData = $this->group_leader->getData($id);
            return $getData;
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[GroupLeaderController::get] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function  update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[GroupLeaderController::update] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->group_leader->updateData($postData)) {
                return pesan(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[GroupLeaderController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[GroupLeaderController::delete] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
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

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            $this->group_leader->deleteData($id);
            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[GroupLeaderController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->group_leader->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            log_message('error', '[GroupLeaderController::export] Unexpected error occured : {err} from {ip}', ['err' => $result, 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception($result, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[GroupLeaderController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[GroupLeaderController::seedData] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $getData = $this->group_leader->getAllData();
            return $this->response->setJSON($getData, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[GroupLeaderController::seedData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function generateEmployeeList()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[GroupLeaderController::generateEmployeeList] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get = $this->group_leader->get_employee_list();

            return pesan(ResponseInterface::HTTP_OK, "Data generated successfully", $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[GroupLeaderController::generateEmployeeList] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
