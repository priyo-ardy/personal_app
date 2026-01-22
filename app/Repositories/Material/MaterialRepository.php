<?php

namespace App\Repositories\Material;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\Material\MaterialModel;
use App\Models\AppSetup\Material\VwMaterialModel;

class MaterialRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new MaterialModel();
    }

    public function checkCode($workshop, $code)
    {
        return $this->model->where('workshop', $workshop)->where('code', $code)->first();
    }

    public function getPrevData(string $code, string $category)
    {
        return $this->model->groupStart()
            ->where('category', $category)
            ->where('code <', $code)
            ->groupEnd()
            ->orWhere('category <', $category) // ATAU Workshop sebelumnya
            ->orderBy('category', 'DESC') // Urutkan Workshop Z-A (Mundur)
            ->orderBy('code', 'DESC')     // Urutkan Code 9-0 (Mundur)
            ->first();
    }

    public function getNextData(string $code, string $category)
    {
        return $this->model->groupStart() // Buka kurung (
            ->where('category', $category)
            ->where('code >', $code)
            ->groupEnd() // Tutup kurung )
            ->orWhere('category >', $category) // ATAU Workshop selanjutnya
            ->orderBy('category', 'ASC') // Urutkan Workshop A-Z
            ->orderBy('code', 'ASC')     // Urutkan Code 0-9
            ->first();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $model = new VwMaterialModel();
        return $model->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
