<?php


namespace App\Repositories\SpecialLeave;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\SpecialLeave\SpecialLeaveModel;

class SpecialLeaveRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new SpecialLeaveModel();
    }
}
