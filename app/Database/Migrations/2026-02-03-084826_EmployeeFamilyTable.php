<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeFamilyTable extends Migration
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
            'row_no' => [
                'type' => 'INT',
                'null' => false,
                'default' => 1
            ],
            'relation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'ocupation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
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
        $this->forge->addKey('employee_id');
        $this->forge->addForeignKey('employee_id', 'm_karyawan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('relation', 'm_family_relation', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('ocupation', 'm_occupation', 'id', '', 'RESTRICT');

        $this->forge->createTable('m_karyawan_keluarga', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_karyawan_keluarga', true);
    }
}
