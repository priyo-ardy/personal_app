<?php

namespace App\Repositories;

use App\Models\AppSetup\Position\PositionModel;

class PositionRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new PositionModel();
    }

    public function findById(string $id)
    {
        return $this->find($id);
    }

    public function generateData()
    {
        return $this->all('code', 'asc');
    }

    public function save(array $data)
    {
        return $this->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->model->update($id, $data);
        // return $this->update($id, $data);
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        return $this->getChunkedData($offset, $limit, $order, $column);
    }

    function nextUser($code)
    {
        return $this->nextData('code', $code);
    }

    function prevUser($code)
    {
        return $this->prevData('code', $code);
    }

    function massDelete($data)
    {
        return $this->model->update($data, ['user_status' => 'inactive', 'deleted_at' => date('Y-m-d H:i:sP')]);
    }
}
