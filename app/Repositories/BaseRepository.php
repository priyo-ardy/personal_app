<?php

namespace App\Repositories;

use CodeIgniter\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findAll()
    {
        return $this->model->findAll();
    }

    // Mengambil satu data by ID
    public function findById($id)
    {
        return $this->model->find($id);
    }

    // Create / Insert
    public function create(array $data)
    {
        return $this->model->insert($data);
    }

    // Update
    public function update($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    // Delete
    public function delete($id)
    {
        return $this->model->delete($id);
    }

    // Mass Delete
    public function massDelete(array $id)
    {
        return $this->model->update($id, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }
}
