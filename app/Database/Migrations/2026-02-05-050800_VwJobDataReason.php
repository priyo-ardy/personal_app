<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwJobDataReason extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_job_data_reason");
        $this->db->query("
            CREATE VIEW vw_job_data_reason AS
            SELECT 
                A.id,
                A.code,
                A.action,
                B.name as action_name,
                A.name,
                A.description,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_job_data_reason AS A
            LEFT JOIN m_job_data_action AS B ON A.action = B.id
            ORDER BY A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_job_data_reason");
    }
}
