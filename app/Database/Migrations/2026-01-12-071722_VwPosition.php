<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwPosition extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_position");
        $this->db->query("
            CREATE VIEW vw_position AS
                SELECT 
                    A.id,
                    A.code,
                    A.name,
                    A.description,
                    A.nbhx_position,
                    B.name AS nbhx_position_name,
                    A.dept,
                    C.name AS dept_name,
                    A.section,
                    D.name AS section_name,
                    A.report_to,
                    E.name AS report_to_position,
                    A.grade,
                    F.name AS grade_name,
                    A.rank,
                    G.name AS rank_name,
                    A.emp_status,
                    CASE
                        WHEN A.emp_status = 'REG' THEN 'Regular'
                        WHEN A.emp_status = 'TMP' THEN 'Temporary'
                    END AS status_name,
                    A.category,
                    H.name as category_name,
                    A.nbhx_category,
                    I.name as nbhx_category_name,
                    A.effective_date,
                    A.hitung_absen,
                    CASE
                        WHEN A.hitung_absen = '0' THEN 'No'
                        WHEN A.hitung_absen = '1' THEN 'Yes'
                    END AS nama_hitung_absen,
                    A.hitung_lembur,
                    CASE
                        WHEN A.hitung_lembur = '0' THEN 'No'
                        WHEN A.hitung_lembur = '1' THEN 'Yes'
                    END AS nama_hitung_lembur,
                    A.created_at,
                    A.created_by,
                    A.updated_at,
                    A.updated_by,
                    A.deleted_at
                FROM m_position AS A 
                    LEFT JOIN m_nbhx_position AS B ON A.nbhx_position = B.id
                    LEFT JOIN m_department AS C ON A.dept = C.id
                    LEFT JOIN m_section AS D ON A.section = D.id
                    LEFT JOIN m_position AS E ON A.report_to = E.id
                    LEFT JOIN m_grade AS F ON A.grade = F.id
                    LEFT JOIN m_employee_rank AS G ON A.rank = G.id
                    LEFT JOIN m_employee_category AS H ON A.category = H.id
                    LEFT JOIN m_class_nbhx AS I ON A.nbhx_category = I.id
                ORDER BY A.code ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_position");
    }
}
