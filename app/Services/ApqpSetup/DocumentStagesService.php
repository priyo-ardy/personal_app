<?php

namespace App\Services\ApqpSetup;

use App\Repositories\ApqpSetup\DocumentStagesRepository;
use App\Validation\ApqpSetup\DocumentStagesValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Config\Database;
use Config\Services;

class DocumentStagesService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(DocumentStagesRepository $repo)
    {
        $this->repository = $repo;
        $this->validation = Services::validation();
        $this->db = Database::connect();
    }
}
