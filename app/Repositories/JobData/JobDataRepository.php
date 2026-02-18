<?php

namespace App\Repositories\JobData;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\JobData\JobDataModel;

class JobDataRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new JobDataModel();
    }
}
