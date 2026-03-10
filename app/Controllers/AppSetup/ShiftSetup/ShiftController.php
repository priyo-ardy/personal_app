<?php

namespace App\Controllers\AppSetup\ShiftSetup;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\OvertimeSetup\OvertimeSetupService;
use App\Repositories\OvertimeSetup\OvertimeSetupRepository;
use App\Service\App\Services\AbsenceStatus\AbsenceStatusService;
use App\Repositories\AbsenceStatus\AbsenceStatusRepository;
use App\Services\AbsenceStatus\AbsenceStatusService as AbsenceStatusAbsenceStatusService;

class ShiftController extends BaseController
{
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

    function calculateJamKerja() {}

    function calculateOvertime() {}
}
