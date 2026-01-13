<?php

namespace App\Repositories;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\Country\CountryModel;
use CodeIgniter\Model;

class CountryRepository extends CrudRepository
{
    protected $model;
    protected $crudRepo;

    public function __construct()
    {
        $this->model = new CountryModel();
    }

    public function getNewCode()
    {
        return $this->generateCode('CNTRY-', 'code', 4);
    }

    public function saveData(array $data)
    {
        return parent::create($data);
    }

    public function findData(string $id)
    {
        return parent::find($id);
    }

    public function updateData(string $id, array $data)
    {
        return parent::update($id, $data);
    }

    public function deleteData(array $data)
    {
        return $this->model->update($data, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        return $this->getChunkedData($offset, $limit, $order, $column);
    }
}
