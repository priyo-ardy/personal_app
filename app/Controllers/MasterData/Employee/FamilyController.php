<?php

namespace App\Controllers\MasterData\Employee;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\FamilyRelation\FamilyRelationService;
use App\Repositories\FamilyRelation\FamilyRelationRepository;
use App\Services\FamilyOccupation\FamilyOccupationService;
use App\Repositories\FamilyOccupation\FamilyOccupationRepository;
use App\Services\Employee\EmployeeService;
use App\Repositories\Employee\EmployeeRepository;
use App\Services\Employee\FamilyService;
use App\Repositories\Employee\FamilyRepository;

class FamilyController extends BaseController
{
    protected $relasi;
    protected $pekerjaan;
    protected $keluarga;
    protected $karyawan;

    public function __construct()
    {
        $this->relasi = new FamilyRelationService(new FamilyRelationRepository());
        $this->pekerjaan = new FamilyOccupationService(new FamilyOccupationRepository());
        $this->karyawan = new EmployeeService(new EmployeeRepository());
        $this->keluarga = new FamilyService(new FamilyRepository());
    }

    public function index()
    {
        //
    }

    public function add(string $token)
    {
        $data = [
            'title' => "Register new employee family member",
            'token' => $token,
            'karyawan' => $this->karyawan->getData(dekripsi($token)),
            'relasi' => $this->relasi->getAllData(),
            'pekerjaan' => $this->pekerjaan->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js"></script>',
                '<script src="' . base_url() . 'js/MasterData/Employee/add_family.js' . '"></script>'
            ]
        ];

        return view('MasterData/Employee/keluarga', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[FamilyController::save] Request not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_BAD_REQUEST, "Request not allowed");
        }

        try {
            $postData = $this->request->getPost();
            $save = $this->keluarga->saveData($postData);
            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully", $save);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[FamilyController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
