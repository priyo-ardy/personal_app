<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeEducationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'uuid',
                'null' => false
            ],
            'employee_id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'row_no' => [
                'type' => 'INT',
                'null' => false,
                'default' => 1
            ],
            'degree' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'major' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'school_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'year_graduated' => [
                'type' => 'INT',
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
        $this->forge->addForeignKey('employee_id', 'm_karyawan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('degree', 'm_degree', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->createTable('m_karyawan_pendidikan', true);
    }

    public function down()
    {
        $this->forge->createTable('m_karyawan_pendidikan', true);
    }
}
