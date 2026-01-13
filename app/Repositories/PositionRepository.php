<?php

namespace App\Repositories;

use App\Models\AppSetup\Position\PositionModel;
use App\Models\AppSetup\Position\VwPositionModel;

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

    public function generateNewCode()
    {
        return $this->generateCode('POS-', 'code', 4);
    }

    public function generatePositionList()
    {
        return $this->model->where('effective_date <=', date('Y-m-d'))->orderBy('code', 'asc')->findAll();
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

    public function delete(string $id)
    {
        return $this->model->delete($id);
    }

    public function deleteAll($data)
    {
        return $this->model->update($data, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new VwPositionModel();
        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
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
