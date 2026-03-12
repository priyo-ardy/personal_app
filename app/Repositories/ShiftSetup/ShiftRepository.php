<?php

namespace App\Repositories\ShiftSetup;

use App\Models\AppSetup\ShiftSetup\ShiftModel;
use App\Models\AppSetup\ShiftSetup\VwShiftModel;
use App\Repositories\CrudRepository;

class ShiftRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new ShiftModel();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $model = new VwShiftModel();

        return $model->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
