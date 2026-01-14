<?php

namespace App\Repositories;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\TempatLahir\TempatLahirModel;

class TempatLahirRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new TempatLahirModel();
    }
}
