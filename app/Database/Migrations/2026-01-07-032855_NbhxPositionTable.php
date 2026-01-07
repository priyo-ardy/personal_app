<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NbhxPositionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'code' => [
                'type' => "VARCHAR",
                'constraint' => 20,
                'null' => false
            ],
            'name' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false
            ],
            'effective_date' => [
                'type' => "DATE",
                'null' => false
            ],
            'description' => [
                'type' => "TEXT",
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

        $this->forge->createTable('m_nbhx_position', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_nbhx_position', true);
    }
}
