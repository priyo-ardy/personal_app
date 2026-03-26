<?php

namespace App\Controllers\AppSetup\ApqpSetup;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DocumentStagesController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Document Stages Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/DocumentStages/stages.js' . '"></script>'
            ]
        ];

        return view('AppSetup/DocumentStages/index', $data);
    }
}
