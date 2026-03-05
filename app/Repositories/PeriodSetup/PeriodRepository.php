<?php

namespace App\Repositories\PeriodSetup;

use App\Models\AppSetup\PeriodSetup\PeriodModel;
use App\Repositories\CrudRepository;

class PeriodRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new PeriodModel();
    }

    public function findDataByUser(string $user_name)
    {
        return $this->model->where('user_name', $user_name)->first();
    }

    public function updateByUser(string $user_name, array $data)
    {
        return $this->model->where('user_name', $user_name)->set($data)->update();
    }
}
