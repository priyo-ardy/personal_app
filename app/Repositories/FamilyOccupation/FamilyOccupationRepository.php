<?php

namespace App\Repositories\FamilyOccupation;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\FamilyOccupation\FamilyOccupationModel;

class FamilyOccupationRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new FamilyOccupationModel();
    }
}
