<?php

namespace App\Repositories\Machine;

use App\Models\AppSetup\Machine\MachineModel;
use App\Models\AppSetup\Machine\VwMachine;
use App\Repositories\CrudRepository;


class MachineRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new MachineModel();
    }

    public function getCodeByWorkshop($workshop, $code)
    {
        return $this->model->where('workshop', $workshop)->where('code', $code)->first();
    }

    public function getPrevData(string $code, string $workshop)
    {
        return $this->model->groupStart()
            ->where('workshop', $workshop)
            ->where('code <', $code)
            ->groupEnd()
            ->orWhere('workshop <', $workshop) // ATAU Workshop sebelumnya
            ->orderBy('workshop', 'DESC') // Urutkan Workshop Z-A (Mundur)
            ->orderBy('code', 'DESC')     // Urutkan Code 9-0 (Mundur)
            ->first();
    }

    public function getNextData(string $code, string $workshop)
    {
        return $this->model->groupStart() // Buka kurung (
            ->where('workshop', $workshop)
            ->where('code >', $code)
            ->groupEnd() // Tutup kurung )
            ->orWhere('workshop >', $workshop) // ATAU Workshop selanjutnya
            ->orderBy('workshop', 'ASC') // Urutkan Workshop A-Z
            ->orderBy('code', 'ASC')     // Urutkan Code 0-9
            ->first();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new VwMachine();
        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
