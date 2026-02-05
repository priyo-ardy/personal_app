<?php

namespace App\Repositories\JobDataAction;

use App\Models\AppSetup\JobDataAction\JobDataActionModel;
use App\Repositories\CrudRepository;

class JobDataActionRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new JobDataActionModel();
    }
}
