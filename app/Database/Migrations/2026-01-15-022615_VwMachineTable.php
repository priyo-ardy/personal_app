<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwMachineTable extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_machine");
        $this->db->query("
            CREATE VIEW vw_machine AS
            SELECT 
                A.id,
                A.code,
                A.name,
                A.specification,
                A.workshop,
                B.name AS workshop_name,
                A.brand,
                A.serial_no,
                A.tonnage,
                C.name AS tonnage_name,
                A.rate,
                A.mfg_date,
                A.puchase_date,
                A.description,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_machine AS A
                LEFT JOIN m_workshop AS B ON A.workshop = B.id
                LEFT JOIN m_tonnage AS C ON A.tonnage = C.id
            ORDER BY 
                A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_machine");
    }
}
