<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwMasterShift extends Migration
{
    public function up()
    {
        $this->db->query('DROP VIEW IF EXISTS vw_master_shift');
        $this->db->query("
            DROP VIEW IF EXISTS vw_master_shift;
            CREATE VIEW vw_master_shift AS
            SELECT 
                A.*,
                CASE
                    WHEN A.overday = true then 'Yes'
                    ELSE 'No'
                END AS nama_overday,
                CASE
                    WHEN A.working_hour_type = 'REG' THEN 'Regular Working Hour'
                    WHEN A.working_hour_type = 'PDK' THEN 'Short Working Hour'
                    ELSE 'Holiday'
                END as working_type_name,
                CASE
                    WHEN A.auto_overtime = true then 'Yes'
                    ELSE 'No'
                END AS auto_overtime_name,
                CASE
                    WHEN A.overtime_type = '1' THEN 'Overtime In'
                    WHEN A.overtime_type = '2' THEN ' Overtime Out'
                    ELSE null
                END AS overtime_type_name,
                B.name AS overtime_name,
                C.name AS absence_name
            FROM m_shift AS A
                LEFT JOIN m_overtime AS B ON A.default_overtime = B.id
                LEFT JOIN m_absence_status AS C ON A.default_absence_status = C.id
            WHERE A.deleted_at IS NULL
        ");
    }

    public function down()
    {
        $this->db->query('DROP VIEW IF EXISTS vw_master_shift');
    }
}
