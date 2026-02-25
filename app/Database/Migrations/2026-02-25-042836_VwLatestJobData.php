<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwLatestJobData extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_latest_job_data");
        $this->db->query(
            "
                CREATE VIEW vw_latest_job_data AS
                SELECT 
                    JD.id AS job_data_id,
                    K.id AS employee_id,
                    K.NIK,
                    K.name AS employee_name,
                    JD.position,
                    P.name AS position_name,
                    P.dept,
                    D.name AS dept_name,
                    P.section,
                    S.name AS section_name,
                    P.nbhx_position,
                    NP.name AS nbhx_position_name,
                    P.report_to,
                    P1.name AS report_to_position,
                    P.grade,
                    G.name AS grade_name,
                    P.rank,
                    ER.name AS rank_name,
                    P.category,
                    C.name AS category_name,
                    P.nbhx_category,
                    CN.name AS nbhx_category_name,
                    P.hitung_absen,
                    P.hitung_lembur,
                    K.tgl_masuk_kerja,
                    JD.effective_date AS on_job_position,
                    JD.action,
                    JDA.name AS action_name,
                    JD.reason,
                    JDR.name AS reason_name,
                    JD.work_relationship,
                    JD.no_contract,
                    JD.durasi_kontrak,
                    JD.tipe_durasi,
                    JD.akhir_kontrak,
                    JD.remark,
                    JD.superior,
                    K1.name as superior_name,
                    K.deleted_at
                FROM m_karyawan AS K
                    LEFT JOIN m_job_data AS JD ON JD.id = (SELECT JD1.id FROM m_job_data AS JD1 WHERE JD1.employee_id = K.id AND JD1.effective_date <= CURRENT_DATE ORDER BY JD1.effective_date DESC LIMIT 1)
                    LEFT JOIN m_position AS P ON JD.position = P.id
                    LEFT JOIN m_department AS D ON P.dept = D.id
                    LEFT JOIN m_section AS S ON P.section = S.id
                    LEFT JOIN m_nbhx_position AS NP ON P.nbhx_position = NP.id
                    LEFT JOIN m_position AS P1 ON P.report_to = P1.id
                    LEFT JOIN m_grade AS G ON P.grade = G.id
                    LEFT JOIN m_employee_rank AS ER ON P.rank = ER.id
                    LEFT JOIN m_employee_category AS C ON p.category = C.id
                    LEFT JOIN m_class_nbhx AS CN ON P.nbhx_category = CN.id
                    LEFT JOIN m_job_data_action AS JDA ON JD.action = JDA.id
                    LEFT JOIN m_job_data_reason AS JDR ON JD.reason = JDR.id
                    LEFT JOIN m_karyawan AS K1 ON JD.superior = K1.id
                WHERE JDA.code <> 'JDA-005'
                ORDER BY K.NIK ASC
            "
        );
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_latest_job_data");
    }
}
