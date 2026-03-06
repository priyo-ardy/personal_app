<?php

namespace App\Controllers\AppSetup\OvertimeSetup;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class OvertimeSetupController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Overtime Setup',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Overtimesetup/overtime_setup.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Overtimesetup/index', $data);
    }
}
