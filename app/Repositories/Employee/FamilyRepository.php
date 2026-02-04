<?php

namespace App\Repositories\Employee;

use App\Repositories\CrudRepository;
use App\Models\MasterData\Employee\EmployeeFamilyModel;

class FamilyRepository extends CrudRepository
{

    public function __construct()
    {
        $this->model = new EmployeeFamilyModel();
    }
}
