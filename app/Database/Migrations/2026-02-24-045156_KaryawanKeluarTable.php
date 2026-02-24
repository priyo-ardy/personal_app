<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KaryawanKeluarTable extends Migration
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
            'exit_date' => [
                'type' => 'DATE',
                'null' => false
            ],
            'exit_reason' => [
                'type' => 'UUID',
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
        $this->forge->addKey('employee_id');
        $this->forge->addForeignKey('employee_id', 'm_karyawan', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('m_karyawan_keluar', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_karyawan_keluar', true);
    }
}
