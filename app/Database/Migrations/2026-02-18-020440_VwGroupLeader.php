<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwGroupLeader extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_group_leader");
        $this->db->query("
            CREATE VIEW vw_group_leader AS
            SELECT
                A.id,
                A.employee_id,
                B.nik,
                B.name as nama_karyawan,
                A.remark,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_group_leader AS A
                left join m_karyawan as B on A.employee_id = B.id
            ORDER BY B.nik ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_group_leader");
    }
}
