<?php

namespace App\Controllers\AppSetup\SchedulleSetup;

use App\Controllers\BaseController;
use App\Services\ShiftSetup\ShiftService;
use App\Repositories\ShiftSetup\ShiftRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\SchedulleSetup\SchedulleService;
use App\Repositories\SchedulleSetup\SchedulleRepository;
use App\Traits\KalkulasiTrait;

class SchedulleController extends BaseController
{
    use KalkulasiTrait;
    protected $shift;
    protected $schedulle;

    public function __construct()
    {
        $this->shift = new ShiftService(new ShiftRepository());
        $this->schedulle = new SchedulleService(new SchedulleRepository());
    }

    public function index()
    {
        $data = [
            'title' => 'Schedulle Setup',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SchedulleSetup/schedulle.js' . '"></script>',
            ]
        ];

        return view('AppSetup/SchedulleSetup/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add New Schedulle',
            'shift' => $this->shift->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SchedulleSetup/add.js' . '"></script>',
            ]
        ];

        return view('AppSetup/SchedulleSetup/add', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SchedulleController::save] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $postData = $this->request->getPost();

            $this->schedulle->saveData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $postData = $this->request->getPost();

                $output = $this->schedulle->loadTable($postData);

                return $this->response->setJSON($output, JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function shift(string $token)
    {
        $id = dekripsi($token);
        $data = [];
        $header = $this->schedulle->getData($id);
        $details = $this->schedulle->getShiftData($id);
        // $data = $this->schedulle->getShiftData($id);

        $data = [
            'schedulle_name' => $header['name'],
            'shift_list' => $details
        ];

        return pesan(ResponseInterface::HTTP_OK, "Data found", $data);
    }

    public function get(string $token)
    {
        $id = dekripsi($token);


        $get = $this->schedulle->getData($id);

        return pesan(ResponseInterface::HTTP_OK, "Data found", $get['token']);
    }

    public function show(string $token)
    {
        $id = dekripsi($token);
        $get = $this->schedulle->getData($id);

        $labelhari = $this->getSchedulleDay($get['total_day']);


        $data = [
            'title' => 'Edit Schedulle Data | ' . $get['name'],
            'data' => $get,
            'label_hari' => $labelhari,
            'details' => $this->schedulle->getShiftData($id),
            'shift' => $this->shift->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SchedulleSetup/show.js' . '"></script>',
            ]
        ];

        return view('AppSetup/SchedulleSetup/show', $data);
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SchedulleController::update] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $postData = $this->request->getPost();

            $this->schedulle->updateData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data updated successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SchedulleController::delete] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
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
            $id = dekripsi($token);

            $this->schedulle->deleteData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function prev()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SchedulleController::prev] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
            throw new \Exception('Request method not valid', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = $json_data['code'];

            $prev = $this->schedulle->prevData($code);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $prev);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::prev] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function next()
    {
        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = $json_data['code'];

            $next = $this->schedulle->nextData($code);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $next);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::next] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function deleteAll()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SchedulleController::deleteAll] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
            throw new \Exception('Request method not valid', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
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

            $this->schedulle->massDelete($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::deleteAll] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->schedulle->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Failed to export data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
