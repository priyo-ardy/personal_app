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

    public function getApproverByApqp(string $apqp_id)
    {
        return $this->model->select('m_apqp_approver.*, m_karyawan.nik, m_karyawan.name')
            ->join('m_karyawan', 'm_apqp_approver.approver = m_karyawan.id', 'left')
            ->where('m_apqp_approver.id_apqp', $apqp_id)->orderBy('row_no', 'asc')->findAll();
    }
}
