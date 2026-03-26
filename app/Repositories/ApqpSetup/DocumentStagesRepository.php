<?php

namespace App\Repositories\ApqpSetup;

use App\Models\AppSetup\ApqpSetup\DocumentStagesModel;
use App\Repositories\CrudRepository;

class DocumentStagesRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentStagesModel();
    }
}
