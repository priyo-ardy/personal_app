<?php

namespace App\Repositories\AbsenceStatus;

use App\Models\AppSetup\AbsenceStatus\AbsenceStatusModel;
use App\Repositories\CrudRepository;

class AbsenceStatusRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new AbsenceStatusModel();
    }

    public function findDataByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }
}
