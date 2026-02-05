<?php

namespace App\Repositories\Employee;

use App\Repositories\CrudRepository;
use App\Models\MasterData\Employee\EducationModel;
use CodeIgniter\Model;

class EmployeeEducationRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EducationModel();
    }
}
