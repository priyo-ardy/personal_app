<?php

namespace App\Repositories\Workshop;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\Workshop\WorkshopModel;
use CodeIgniter\Model;

class WorkshopRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new WorkshopModel();
    }
}
