<?php

namespace App\Controllers\AppSetup\ProcessRoute;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Repositories\ProcessRoute\ProcessRouteRepository;
use App\Services\ProcessRoute\ProcessRouteService;
use App\Models\AppSetup\ProcessRoute\ProcessRouteModel;
use App\Traits\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class ProcessRouteController extends BaseController
{
    use ResponseTrait;
    protected $route;

    public function __construct()
    {
        $this->route = new ProcessRouteService(new ProcessRouteRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();
                $output = $this->route->loadTable($requestData);
                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProcessRouteController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => "Process Route Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/ProcessRoute/process_route.js' . '"></script>'
            ]
        ];

        return view('AppSetup/ProcessRoute/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ProcessRouteController::save] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->route->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProcessRouteController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[ProcessRouteController::get] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $data = $this->route->getData($id);

            if ($data instanceof \Exception) {
                throw $data;
            }

            return $this->success(ResponseInterface::HTTP_OK, "Retrive data success", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProcessRouteController::get] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ProcessRouteController::update] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->route->updateData($data)) {
                log_message('info', '[ProcessRouteController::update] Data updated successfully from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
                return $this->success(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProcessRouteController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ProcessRouteController::delete] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            if ($this->route->deleteData($id)) {
                log_message('info', "Delete route data successfully with total data {total}", ['total' => count($token)]);
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProcessRouteController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[ProcessRouteController::export] Request method not allowed for user {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $result = $this->route->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            return $this->success(ResponseInterface::HTTP_OK, "Retrive data success", $result);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProcessRouteController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[ProcessRouteController::seedData] Request method not allowed for user {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get_data = $this->route->getAllData();
            if (!$get_data) {
                log_message('error', '[ProcessRouteController::seedData] Data Process Route from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return $this->success(ResponseInterface::HTTP_OK, "Retrive data success", $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProcessRouteController::seedData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
