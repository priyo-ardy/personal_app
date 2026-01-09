<?php

namespace App\Services;

use App\Repositories\PositionRepository;
use App\Traits\ResponseTrait;
use Config\Database;
use Config\Services;

class PositionService
{
    use ResponseTrait;
    protected $db;
    protected $validasi;
    protected $positionRepo;

    public function __construct(PositionRepository $positionRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->positionRepo = $positionRepo;
    }

    public function loadData()
    {
        return $this->positionRepo->all('code', 'asc');
    }
}
