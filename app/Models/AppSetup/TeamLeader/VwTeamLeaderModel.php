<?php

namespace App\Models\AppSetup\TeamLeader;

use App\Models\BaseModel;
use CodeIgniter\Model;

class VwTeamLeaderModel extends BaseModel
{
    protected $table            = 'vw_team_leader';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = [];
}
