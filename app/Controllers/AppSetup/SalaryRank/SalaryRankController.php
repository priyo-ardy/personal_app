<?php

namespace App\Controllers\AppSetup\SalaryRank;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SalaryRankController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Salary Rank Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SalaryRank/salary_rank.js' . '"></script>'
            ]
        ];

        return view('AppSetup/SalaryRank/index', $data);
    }
}
