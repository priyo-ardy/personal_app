<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwApqpDocument extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_apqp_document");
        $this->db->query("
            CREATE VIEW vw_apqp_document AS
            SELECT 
                B.sequence,
                B.name as apqp_name,
                A.*
            FROM m_apqp_document AS A
                LEFT JOIN m_apqp_header AS B ON A.apqp_id = B.id
            WHERE
                A.deleted_at IS NULL
            ORDER BY B.sequence ASC, A.baris ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_apqp_document");
    }
}
