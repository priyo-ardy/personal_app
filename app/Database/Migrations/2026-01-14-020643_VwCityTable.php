<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwCityTable extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_city");
        $this->db->query("
            CREATE VIEW vw_city AS
            SELECT 
                A.id,
                A.code,
                A.province,
                A.name,
                B.name as province_name,
                A.description,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_city AS A
                LEFT JOIN m_province AS B ON A.province = B.id
            ORDER BY A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_city");
    }
}
