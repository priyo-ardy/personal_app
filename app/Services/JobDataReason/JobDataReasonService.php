<?php

namespace App\Services\JobDataReason;

use App\Repositories\JobDataAction\JobDataActionRepository;
use App\Repositories\JobDataReason\JobDataReasonRepository;
use App\Models\AppSetup\JobDataReason\JobDataReasonModel;
use App\Validation\JobDataReason\JobDataReasonValidation;
use Config\Database;
use Config\Services;


class JobDataReasonService
{
    protected $db;
    protected $validation;
    protected $repository;
    protected $action;

    public function __construct(JobDataReasonRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
        $this->action = new JobDataActionRepository();
    }

    public function getDataByAction(string $action)
    {
        try {
            $get_list = $this->repository->getListByAction($action);

            $list = [];

            foreach ($get_list as $row) {
                $list[] = [
                    'token' => $row->id,
                    'name' => $row->name
                ];
            }

            return $list;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::getDataByAction] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
