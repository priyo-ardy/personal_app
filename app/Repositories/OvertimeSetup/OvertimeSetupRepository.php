<?php

namespace App\Repositories\OvertimeSetup;

use App\Models\AppSetup\OvertimeSetup\OvertimeSetupModel;
use App\Repositories\CrudRepository;

class OvertimeSetupRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new OvertimeSetupModel();
    }
}
