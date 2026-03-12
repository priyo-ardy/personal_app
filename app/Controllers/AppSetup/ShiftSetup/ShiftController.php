<?php

namespace App\Controllers\AppSetup\ShiftSetup;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\ShiftSetup\ShiftService;
use App\Repositories\ShiftSetup\ShiftRepository;
use App\Services\OvertimeSetup\OvertimeSetupService;
use App\Repositories\OvertimeSetup\OvertimeSetupRepository;
use App\Repositories\AbsenceStatus\AbsenceStatusRepository;
use App\Services\AbsenceStatus\AbsenceStatusService;
use App\Traits\KalkulasiTrait;

class ShiftController extends BaseController
{
    use KalkulasiTrait;
    protected $overtime;
    protected $absence;
    protected $shift;

    public function __construct()
    {
        $this->overtime = new OvertimeSetupService(new OvertimeSetupRepository());
        $this->absence = new AbsenceStatusService(new AbsenceStatusRepository());
        $this->shift = new ShiftService(new ShiftRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $postData = $this->request->getPost();

                $output = $this->shift->loadTable($postData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Shift Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/ShiftSetup/shift.js' . '"></script>'
            ]
        ];

        return view('AppSetup/ShiftSetup/index', $data);
    }

    function add()
    {
        $data = [
            'title' => 'Create New Shift',
            'overtime' => $this->overtime->getAllData(),
            'absence' => $this->absence->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/ShiftSetup/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/ShiftSetup/add', $data);
    }

    function calculateOvertime()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ShiftController::calculateOvertime] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
            throw new \Exception('Request method not valid', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);
            if (!is_array($json_data)) {
                throw new \Exception('Invalid JSON request', ResponseInterface::HTTP_BAD_REQUEST);
            }

            $overtime_type = $json_data['overtime_type'];
            $overtime_start = $json_data['lembur_mulai'];
            $overtime_finish = $json_data['lembur_selesai'];
            $min_ot = $json_data['min_ot'];
            $working_hour = $json_data['working_hour'];
            $get_rate = $this->overtime->getData($overtime_type);

            $kalkulasi = $this->kalkulasi_overtime($overtime_type, $overtime_start, $overtime_finish, $working_hour, $min_ot, $get_rate['rate']);
            return $this->response->setJSON($kalkulasi, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::calculateOvertime] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ShiftController::save] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
            throw new \Exception('Request method not valid', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();
            $get_rate = $this->overtime->getData($postData['overtime_type']);
            $save = $this->shift->saveData($postData, $get_rate['rate']);

            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully", $save);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[ShiftController::get] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
            throw new \Exception('Request method not valid', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $getData = $this->shift->getData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $getData['token']);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::get] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function show($token)
    {
        $id = dekripsi($token);
        $getData = $this->shift->getData($id);

        $data = [
            'title' => 'View Shift Data | ' . $getData['name'],
            'token' => enkripsi($id),
            'data' => $getData,
            'overtime' => $this->overtime->getAllData(),
            'absence' => $this->absence->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/ShiftSetup/show.js' . '"></script>'
            ]
        ];

        return view('AppSetup/ShiftSetup/show', $data);
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ShiftController::update] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
            throw new \Exception('Request method not valid', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $get_rate = $this->overtime->getData($postData['overtime_type']);

            $update = $this->shift->updateData($postData, $get_rate['rate']);
            return pesan(ResponseInterface::HTTP_OK, "Data updated successfully", $update);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ShiftController::delete] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
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
            $id = dekripsi($token);

            $this->shift->deleteData($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function deleteAll()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ShiftController::deleteAll] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
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

            $this->shift->massDelete($id);

            return pesan(ResponseInterface::HTTP_OK, "Data deleted successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::deleteAll] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->shift->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function prev()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ShiftController::prev] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
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

            $pev_data = $this->shift->prevData($code);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $pev_data['token']);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::prev] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function next()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ShiftController::next] Request method not valid, request method : {method}', ['method' => $this->request->getMethod()]);
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

            $next_data = $this->shift->nextData($code);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $next_data['token']);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::next] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
