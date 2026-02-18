<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwTeamLeader extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_team_leader");
        $this->db->query("
            CREATE VIEW vw_team_leader AS
            SELECT 
                A.id,
                A.employee_id,
                B.nik,
                B.name,
                A.remark,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at 
            FROM m_team_leader AS A
                LEFT JOIN m_karyawan AS B ON A.employee_id = B.id
            ORDER BY B.nik ASC
        ");
    }

    public function down()
    {
        //
    }
}
