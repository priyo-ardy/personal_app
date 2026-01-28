<?php

namespace App\Repositories\EducationDegree;

use App\Models\AppSetup\EducationDegre\EducationDegreeModel;
use App\Repositories\CrudRepository;

class EducationDegreeRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EducationDegreeModel();
    }
}
