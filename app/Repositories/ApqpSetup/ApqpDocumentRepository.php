<?php

namespace App\Repositories\ApqpSetup;

use App\Models\AppSetup\ApqpSetup\ApqpDocumentModel;

class ApqpDocumentRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new ApqpDocumentModel();
    }

    public function getDocumentByApqp(string $apqp)
    {
        return $this->model->where('apqp_id', $apqp)->orderBy('baris', 'asc')->findAll();
    }
}
