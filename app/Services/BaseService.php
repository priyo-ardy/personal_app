<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Faker\Provider\Base;

class BaseService
{
    protected $repository;

    public function __construct(BaseRepository $baseRepo)
    {
        $this->repository = $baseRepo;
    }

    public function getAllData() {}

    public function saveData(array $data) {}

    public function getData(string $id) {}

    public function updateData(array $data) {}

    public function deleteData(string $id) {}

    public function deleteAll(array $data) {}

    public function exportData() {}
}
