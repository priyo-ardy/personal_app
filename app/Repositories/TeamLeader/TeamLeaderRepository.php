<?php

namespace App\Repositories\TeamLeader;

use App\Models\AppSetup\TeamLeader\TeamLeaderModel;
use App\Models\AppSetup\TeamLeader\VwTeamLeaderModel;
use App\Repositories\CrudRepository;
use CodeIgniter\Model;

class TeamLeaderRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new TeamLeaderModel();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new VwTeamLeaderModel();
        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    public function getEmployeeList()
    {
        $db = \Config\Database::connect();

        // Gunakan huruf kecil untuk alias 'b' agar konsisten dengan perilaku Postgres
        $subquery = $db->table('m_team_leader as b')
            ->select('b.employee_id')
            ->where('b.deleted_at IS NULL')
            ->getCompiledSelect();

        // Gunakan huruf kecil untuk alias 'a'
        $builder = $db->table('m_karyawan as a');

        // Bungkus identifier dengan tanda kutip manual atau gunakan huruf kecil saja
        $builder->where("a.id NOT IN ($subquery)", null, false);
        $builder->orderBy('a.nik', 'ASC');

        $query = $builder->get();
        $result = $query->getResultObject();

        return $result;
    }

    public function permanentDelete(array $id)
    {
        return $this->model->delete($id, true);
    }
}
