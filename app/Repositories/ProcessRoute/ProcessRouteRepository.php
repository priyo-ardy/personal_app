<?php

namespace App\Repositories\ProcessRoute;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\ProcessRoute\ProcessRouteModel;
use CodeIgniter\Model;

class ProcessRouteRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new ProcessRouteModel();
    }
}
