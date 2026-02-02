<?php

namespace App\Services\Employee;

use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\DataTableRepository;
use App\Validation\Employee\EmployeeValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\ResponseTrait;

class EmployeeService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = new EmployeeRepository();
    }

    public function newNik(string $category)
    {
        try {
            $nik = $this->repository->getNewNik($category);
            return $nik;
        } catch (\Exception $e) {
            log_message('error', "[EmployeeService::newNik] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
