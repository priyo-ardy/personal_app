<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwProvince extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_province");
        $this->db->query("
            CREATE VIEW vw_province AS
            SELECT
                A.id,
                A.code,
                A.country,
                B.name as country_name,
                A.name,
                A.description,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_province AS A
                LEFT JOIN m_country AS B ON A.country = B.id
            ORDER BY A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_province");
    }
}
