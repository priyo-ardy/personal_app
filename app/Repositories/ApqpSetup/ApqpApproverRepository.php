<?php

namespace App\Repositories\ApqpSetup;

use App\Models\AppSetup\ApqpSetup\ApqpApproverModel;

class ApqpApproverRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new ApqpApproverModel();
    }
}
