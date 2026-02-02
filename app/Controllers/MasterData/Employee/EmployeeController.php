<?php

namespace App\Controllers\MasterData\Employee;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Employee\EmployeeService;
use App\Repositories\Employee\EmployeeRepository;
use App\Services\Location\LocationService;
use App\Repositories\Location\LocationRepository;
use App\Services\TempatLahirService;
use App\Repositories\TempatLahirRepository;
use App\Services\ProvinceService;
use App\Repositories\ProvinceRepository;
use App\Services\FamilyRelation\FamilyRelationService;
use App\Repositories\FamilyRelation\FamilyRelationRepository;

class EmployeeController extends BaseController
{
    protected $location;
    protected $tempat_lahir;
    protected $province;
    protected $relasi;
    protected $employee;

    public function __construct()
    {
        $this->employee = new EmployeeService(new EmployeeRepository());
        $this->location = new LocationService(new LocationRepository());
        $this->tempat_lahir = new TempatLahirService(new TempatLahirRepository());
        $this->province = new ProvinceService(new ProvinceRepository());
        $this->relasi = new FamilyRelationService(new FamilyRelationRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Employee Management",
            'footer' => [
                '<script src="' . base_url() . 'js/AppSetup/datatable.js"></script>',
                '<script src="' . base_url() . 'js/MasterData/Employee/employee.js' . '"></script>'
            ]
        ];

        return view('MasterData/Employee/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Register New Employee",
            'location' => $this->location->getAllData(),
            'tempat_lahir' => $this->tempat_lahir->loadAllData(),
            'province' => $this->province->loadAllData(),
            'relasi' => $this->relasi->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js"></script>',
                '<script src="' . base_url() . 'js/MasterData/Employee/add.js' . '"></script>'
            ]
        ];

        return view('MasterData/Employee/add', $data);
    }

    public function generateNik()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', 'Request method not allowed for generate nik : {method} from {ip} by {NIK}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR'], 'NIK' => session()->get('user_name')]);
            throw new \Exception('Request not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);
            $category = $json_data['category'];

            $nik = $this->employee->newNik($category);
            return pesan(ResponseInterface::HTTP_OK, "NIK generated successfully", $nik);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[EmployeeController::generateNik] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
