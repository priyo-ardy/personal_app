<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwSection extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_section");

        $this->db->query(
            "
                CREATE VIEW vw_section AS 
                SELECT
                    m_section.id,
                    m_section.code,
                    m_section.dept,
                    m_department.name as dept_name,
                    m_section.name,
                    m_section.effective_date,
                    m_section.description,
                    m_section.created_at,
                    m_section.created_by,
                    m_section.updated_at,
                    m_section.updated_by,
                    m_section.deleted_at
                FROM m_section
                LEFT JOIN m_department ON m_section.dept = m_department.id
                ORDER BY m_section.code ASC
            "
        );
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_section");
    }
}
