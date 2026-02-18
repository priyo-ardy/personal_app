<?php

namespace App\Controllers\AppSetup\JobDataAction;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\JobDataAction\JobDataActionService;
use App\Repositories\JobDataAction\JobDataActionRepository;

class JobDataActionController extends BaseController
{
    protected $action;

    public function __construct()
    {
        $this->action = new JobDataActionService(new JobDataActionRepository());
    }

    public function loadTable()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[JobDataActionController::loadTable] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();
                $output = $this->action->loadTable($requestedData);
                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataActionController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Job Data Action Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/JobDataAction/job_data_action.js' . '"></script>'
            ]
        ];

        return view('AppSetup/JobDataAction/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[JobDataActionController::save] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->action->saveData($postData)) {
                return pesan(ResponseInterface::HTTP_OK, 'Data saved successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[JobDataActionController::save] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get(string $token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[JobDataActionController::get] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $result = $this->action->getData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data loaded successfully", $result);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[JobDataActionController::get] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[JobDataActionController::update] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->action->updateData($postData)) {
                return pesan(ResponseInterface::HTTP_OK, 'Data updated successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[JobDataActionController::update] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[JobDataActionController::delete] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
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

            if ($this->action->deleteData($id)) {
                return pesan(ResponseInterface::HTTP_OK, 'Data deleted successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[JobDataActionController::delete] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[JobDataActionController::export] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $result = $this->action->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Unexpected error occured", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[JobDataActionController::export] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[JobDataActionController::seedData] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get = $this->action->getAllData();

            return pesan(ResponseInterface::HTTP_OK, "Data seeded successfully", $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[JobDataActionController::seedData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function generateList()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[JobDataActionController::generateList] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get = $this->action->generateList();

            return pesan(ResponseInterface::HTTP_OK, "Data list generated successfully", $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[JobDataActionController::generateList] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
