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
    }

    public function update(string $id, array $data)
    {
        return $this->model->update($id, $data);
    }
}
