<?php

namespace App\Controllers\MasterData\Employee;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Employee\EmployeeService;
use App\Repositories\Employee\EmployeeRepository;
use App\Services\EducationDegree\EducationDegreeService;
use App\Repositories\EducationDegree\EducationDegreeRepository;
use App\Services\Employee\EmployeeEducationService;
use App\Repositories\Employee\EmployeeEducationRepository;

class EducationController extends BaseController
{
    protected $karyawan;
    protected $pendidikan;
    protected $education;

    public function __construct()
    {
        $this->karyawan = new EmployeeService(new EmployeeRepository());
        $this->pendidikan = new EducationDegreeService(new EducationDegreeRepository());
        $this->education = new EmployeeEducationService(new EmployeeEducationRepository());
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
            'pendidikan' => $this->pendidikan->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/MasterData/Employee/add_education.js' . '"></script>'
            ]
        ];

        return view('MasterData/Employee/pendidikan', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[EducationController::save] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $save = $this->education->saveData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Employee education data successfully registered", $save);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[EducationController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
