<?php

namespace App\Services\Employee;

use App\Repositories\Employee\EmployeeJobDataRepository;
use App\Models\MasterData\Employee\EmployeeJobDataModel;
use App\Validation\Employee\EmployeeJobDataValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;

class EmployeeJobDataService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(EmployeeJobDataRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }
}
