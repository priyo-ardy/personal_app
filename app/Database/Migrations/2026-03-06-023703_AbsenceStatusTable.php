<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AbsenceStatusTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "UUID",
                'null' => false
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'description' => [
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
        $this->forge->addKey('code', false, true);
        $this->forge->createTable('m_absence_status');
    }

    public function down()
    {
        $this->forge->dropTable('m_absence_status', true);
    }
}
