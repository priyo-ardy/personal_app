<?php

namespace App\Controllers\AppSetup\ShiftSetup;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\OvertimeSetup\OvertimeSetupService;
use App\Repositories\OvertimeSetup\OvertimeSetupRepository;
use App\Service\App\Services\AbsenceStatus\AbsenceStatusService;
use App\Repositories\AbsenceStatus\AbsenceStatusRepository;
use App\Services\AbsenceStatus\AbsenceStatusService as AbsenceStatusAbsenceStatusService;
use App\Traits\KalkulasiTrait;

class ShiftController extends BaseController
{
    use KalkulasiTrait;
    protected $overtime;
    protected $absence;

    public function __construct()
    {
        $this->overtime = new OvertimeSetupService(new OvertimeSetupRepository());
        $this->absence = new AbsenceStatusAbsenceStatusService(new AbsenceStatusRepository());
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

            $kalkulasi = $this->kalkulasi_overtime($overtime_type, $overtime_start, $overtime_finish, $working_hour, $min_ot);

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
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ShiftController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
