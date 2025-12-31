<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\CrudRepository;
use App\Models\UserModel;

class UserRepository extends CrudRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function findById(string $user_id)
    {
        return $this->model->where('user_id', $user_id)->first();
    }

    public function findByUsername(string $username)
    {
        return $this->model->where('user_name', $username)->first();
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email_hash', $email)->first();
    }

    public function findByPhone(string $phone)
    {
        return $this->model->where('phone_hash', $phone)->first();
    }

    public function create(array $data)
    {
        return $this->model->insert($data);
        // return $this->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->model->update($id, $data);
        // return $this->update($id, $data);
    }

    public function getChunkedData($offset, $limit, $order, $column)
    {
        return $this->model->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    function nextUser($code)
    {
        return $this->nextData('user_name', $code);
    }

    function prevUser($code)
    {
        return $this->prevData('user_name', $code);
    }

    function massDelete($user_data)
    {
        return $this->model->update($user_data, ['user_status' => 'inactive', 'deleted_at' => date('Y-m-d H:i:sP')]);
    }
}
