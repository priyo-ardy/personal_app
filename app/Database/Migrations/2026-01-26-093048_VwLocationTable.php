<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwLocationTable extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_location");
        $this->db->query("
            CREATE VIEW vw_location AS
            SELECT
                A.id,
                A.code,
                A.factory,
                B.name AS factory_name,
                A.name,
                A.address,
                A.description,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_location AS A
                LEFT JOIN m_factory AS B ON A.factory = B.id
            ORDER BY A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_location");
    }
}
