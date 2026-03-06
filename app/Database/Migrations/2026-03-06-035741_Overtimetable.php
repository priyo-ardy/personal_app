<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Overtimetable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
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
            'rate' => [
                'type' => 'JSONB',
                'null' => false
            ],
            'day_type' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'comment' => '0: Work Day, 1: Holiday (Office), 2: Holiday (Off)'
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

        $this->forge->createTable('m_overtime', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_overtime', true);
    }
}
