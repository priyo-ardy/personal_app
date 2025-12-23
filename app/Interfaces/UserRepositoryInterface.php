<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function findById(string $user_id);
    public function findByUsername(string $username);
    public function findByEmail(string $email);
    public function findByPhone(string $phone);
    public function create(array $data);
}
