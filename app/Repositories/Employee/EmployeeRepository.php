<?php

namespace App\Repositories\Employee;

use App\Models\AppSetup\Employee\EmployeeModel;
use App\Repositories\CrudRepository;

class EmployeeRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new EmployeeModel();
    }
}
