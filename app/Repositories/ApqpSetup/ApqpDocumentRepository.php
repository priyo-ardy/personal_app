<?php

namespace App\Repositories\ApqpSetup;

use App\Models\AppSetup\ApqpSetup\ApqpDocumentModel;
use App\Repositories\CrudRepository;
use App\Models\AppSetup\ApqpSetup\VwApqpDocumentModel;

class ApqpDocumentRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new ApqpDocumentModel();
    }

    public function getDocumentByApqp(string $apqp)
    {
        return $this->model->select('m_apqp_document.*, m_karyawan.nik, m_karyawan.name')
            ->join('m_karyawan', 'm_apqp_document.uploader = m_karyawan.id', 'left')
            ->where('apqp_id', $apqp)->orderBy('baris', 'asc')->findAll();
    }

    function getDocumentList()
    {
        $model = new VwApqpDocumentModel();

        return $model->findAll();
    }
}
