<?php

namespace App\Repositories\ApqpSetup;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\ApqpSetup\ApqpHeaderModel;
use CodeIgniter\Model;

class ApqpHeaderRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ApqpHeaderModel();
    }
}
