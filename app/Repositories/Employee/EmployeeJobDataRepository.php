<?php

namespace App\Repositories\Employee;

use App\Models\MasterData\Employee\EmployeeJobDataModel;
use App\Repositories\CrudRepository;

class EmployeeJobDataRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmployeeJobDataModel();
    }
}
