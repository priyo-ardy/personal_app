<?php

namespace App\Repositories\FamilyRelation;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\FamilyRelation\FamilyRelationModel;
use CodeIgniter\Model;

class FamilyRelationRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new FamilyRelationModel();
    }
}
