<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JobDataTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'employee_id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'action' => [
                'type' => 'UUID',
                'null' => false
            ],
            'reason' => [
                'type' => 'UUID',
                'null' => false
            ],
            'effective_date' => [
                'type' => 'DATE',
                'null' => false
            ],
            'work_relationship' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
                'comment' => 'Tetap, Kontrak, Magang, PKL, Harian'
            ],
            'no_contract' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'durasi_kontrak' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0
            ],
            'akhir_kontrak' => [
                'type' => 'DATE',
                'null' => false
            ],
            'superior' => [
                'type' => 'uuid',
                'null' => false
            ],
            'remark' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ]
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('employee_id', false, false);
        $this->forge->addKey('action', false, false);
        $this->forge->addKey('reason', false, false);
        $this->forge->addKey('effective_date', false, false);

        $this->forge->addForeignKey('employee_id', 'm_karyawan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('action', 'm_job_data_action', 'id', '', '');
        $this->forge->addForeignKey('reason', 'm_job_data_reason', 'id', '', '');
        $this->forge->addForeignKey('superior', 'm_karyawan', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->createTable('m_job_data', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_job_data', true);
    }
}
