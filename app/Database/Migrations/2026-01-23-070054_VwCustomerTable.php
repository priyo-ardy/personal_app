<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwCustomerTable extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_customer");
        $this->db->query("
            CREATE VIEW vw_customer AS
            SELECT 
                A.id,
                A.code,
                A.name,
                A.address,
                A.category,
                B.name as category_name,
                A.email,
                A.email_hash,
                A.phone,
                A.phone_hash,
                A.contact_person,
                A.contact_person_email,
                A.contact_person_email_hash,
                A.contact_person_phone,
                A.contact_person_phone_hash,
                A.description,
                A.created_at,
                A.created_by,
                A.updated_at,
                A.updated_by,
                A.deleted_at
            FROM m_customer AS A
                LEFT JOIN m_customer_category AS B ON A.category = B.id
            ORDER BY A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_customer");
    }
}
