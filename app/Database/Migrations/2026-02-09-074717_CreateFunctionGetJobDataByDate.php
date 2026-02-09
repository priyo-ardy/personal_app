<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFunctionGetJobDataByDate extends Migration
{
    public function up()
    {
        $sql = "
            CREATE OR REPLACE FUNCTION get_job_data_by_date(ref_date DATE)
            RETURNS TABLE (
                id UUID,
                employee_id UUID,
                action UUID,
                reason UUID,
                effective_date DATE,
                work_relationship VARCHAR(10),
                no_contract VARCHAR(50),
                durasi_kontrak INT,
                akhir_kontrak DATE,
                superior UUID,
                remark TEXT,
                created_at TIMESTAMPTZ,
                created_by VARCHAR(50),
                updated_at TIMESTAMPTZ,
                updated_by VARCHAR(50),
                deleted_at TIMESTAMPTZ
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT DISTINCT ON (m.employee_id) 
                    m.id, 
                    m.employee_id, 
                    m.action,
                    m.reason,
                    m.effective_date,
                    m.work_relationship,
                    m.no_contract,
                    m.durasi_kontrak,
                    m.akhir_kontrak,
                    m.superior,
                    m.remark,
                    m.created_at,
                    m.created_by,
                    m.updated_at,
                    m.updated_by,
                    m.deleted_at
                FROM m_job_data m
                WHERE m.effective_date <= ref_date 
                    AND m.deleted_at IS NULL
                ORDER BY m.employee_id, m.effective_date DESC, m.created_at DESC;
            END;
            $$ LANGUAGE plpgsql;
        ";

        $this->db->query($sql);
    }

    public function down()
    {
        $this->db->query("DROP FUNCTION IF EXISTS get_job_data_by_date(DATE);");
    }
}
