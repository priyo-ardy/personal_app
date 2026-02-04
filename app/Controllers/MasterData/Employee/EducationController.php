<?php

namespace App\Controllers\MasterData\Employee;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Employee\EmployeeService;
use App\Repositories\Employee\EmployeeRepository;
use App\Services\EducationDegree\EducationDegreeService;
use App\Repositories\EducationDegree\EducationDegreeRepository;

class EducationController extends BaseController
{
    protected $karyawan;
    protected $pendidikan;

    public function __construct()
    {
        $this->karyawan = new EmployeeService(new EmployeeRepository());
        $this->pendidikan = new EducationDegreeService(new EducationDegreeRepository());
    }
    public function index()
    {
        //
    }

    public function add(string $token)
    {
        $employee_id = dekripsi($token);
        $karyawan = $this->karyawan->getData($employee_id);

        $data = [
            'title' => "Register new employee education",
            'token' => $token,
            'karyawan' => $this->karyawan->getData($employee_id),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/MasterData/Employee/add_education.js' . '"></script>'
            ]
        ];

        return view('MasterData/Employee/pendidikan', $data);
    }
}
