<?php

namespace App\Repositories\JobDataReason;

use App\Models\AppSetup\JobDataReason\JobDataReasonModel;
use App\Repositories\CrudRepository;

class JobDataReasonRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new JobDataReasonModel();
    }

    public function getListByAction(string $action)
    {
        return $this->model->where('action', $action)->orderBy('code', 'ASC')->findAll();
    }
}
