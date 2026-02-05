<?php

namespace App\Services\JobDataAction;

use App\Repositories\JobDataAction\JobDataActionRepository;
use App\Models\AppSetup\JobData\JobDataModel;
use App\Models\AppSetup\JobDataAction\JobDataActionModel;
use App\Repositories\DataTableRepository;
use App\Validation\JobDataAction\JobDataActionValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class JobDataActionService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(JobDataActionRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
