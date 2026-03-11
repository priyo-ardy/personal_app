<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VwKaryawanAktif extends Migration
{
    public function up()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_karyawan_aktif");
        $this->db->query("
            CREATE VIEW vw_karyawan_aktif AS
            WITH LatestJobData AS (
                SELECT 
                    id,
                    employee_id,
                    action,
                    reason,
                    position,
                    effective_date,
                    work_relationship,
                    no_contract,
                    durasi_kontrak,
                    tipe_durasi,
                    akhir_kontrak,
                    superior,
                    status,
                    remark,
                    created_at,
                    created_by,
                    updated_at,
                    updated_by,
                    deleted_at,
                    ROW_NUMBER() OVER(
                        PARTITION BY employee_id
                        ORDER BY effective_date DESC
                    ) AS urutan
                FROM 
                    m_job_data 
                WHERE effective_date <= CURRENT_TIMESTAMP
            )

            SELECT 
                A.id,
                A.nik,
                A.name,
                B.name as position_name,
                C.name as dept_name,
                D.name as section_name,
                E.name as category_name,
                jd.status,
                jd.work_relationship,
                A.tgl_masuk_kerja,
                jd.no_contract,
                jd.durasi_kontrak,
                jd.tipe_durasi,
                jd.akhir_kontrak,
                jd.superior,
                F.name as nbhx_position_category,
                G.name as nbhx_position_name,
                H.name as grade_name,
                I.name as rank_name,
                J.nik as superior_nik,
                J.name as superior_name,
                jd.remark,
                A.deleted_at
            FROM m_karyawan as A
                LEFT JOIN LatestJobData jd ON A.id = jd.employee_id AND jd.urutan = 1
                LEFT JOIN m_position AS B ON jd.position = B.id
                LEFT JOIN m_department AS C ON B.dept = C.id
                LEFT JOIN m_section AS D ON B.section = D.id
                LEFT JOIN m_employee_category AS E ON B.category = E.id
                LEFT JOIN m_class_nbhx as F ON B.nbhx_category = F.id
                LEFT JOIN m_nbhx_position as G ON B.nbhx_position = G.id
                LEFT JOIN m_grade as H ON B.grade = H.id
                LEFT JOIN m_employee_rank as I ON B.rank = I.id
                left join m_karyawan AS J ON jd.superior = J.id
            WHERE A.id NOT IN (SELECT employee_id FROM m_karyawan_keluar)
            ORDER BY A.nik ASC
        ");
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_karyawan_aktif");
    }
}
