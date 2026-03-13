<?php

namespace App\Repositories\SchedulleSetup;

use App\Models\AppSetup\SchedulleSetup\SchedulleModel;
use App\Repositories\CrudRepository;

class SchedulleRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new SchedulleModel();
    }
}
