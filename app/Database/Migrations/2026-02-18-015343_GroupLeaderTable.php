<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GroupLeaderTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                null => false
            ],
            'employee_id' => [
                'type' => 'UUID',
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

        $this->forge->createTable('m_group_leader', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_group_leader', true);
    }
}
