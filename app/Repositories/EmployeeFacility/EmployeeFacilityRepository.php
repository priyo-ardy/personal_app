<?php

namespace App\Repositories\EmployeeFacility;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\EmployeeFacility\EmployeeFacilityModel;

class EmployeeFacilityRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmployeeFacilityModel();
    }
}
