<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwOvertimeSetup extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_overtime_setup");
        $this->db->query("
            CREATE VIEW vw_overtime_setup AS
            SELECT 
                *,
                CASE
                    WHEN day_type = '0' THEN 'Work Day'
                    WHEN day_type = '1' THEN 'Holiday (Office)'
                    WHEN day_type = '2' THEN 'Holiday (Off)'
                END AS nama_type
            FROM m_overtime
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_overtime_setup");
    }
}
