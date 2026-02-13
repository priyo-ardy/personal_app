<?php

namespace App\Controllers\Module\Mfg\Spk;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SpkController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'List of SPK',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/Module/Mfg/Spk/spk.js' . '"></script>'
            ]
        ];

        return view('Module/Mfg/Spk/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Register New SPK",
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/Module/Mfg/Spk/add.js' . '"></script>'
            ]
        ];

        return view('Module/Mfg/Spk/add', $data);
    }
}
