<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MachineTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
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
            'specification' => [
                'type' => "TEXT",
                'null' => true
            ],
            'workshop' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'brand' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => true
            ],
            'serial_no' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'tonnage' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'rate' => [
                'type' => "VARCHAR",
                'constraint' => 20,
                'null' => true
            ],
            'mfg_date' => [
                'type' => "DATE",
                'null' => true
            ],
            'puchase_date' => [
                'type' => "DATE",
                'null' => true
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
        $this->forge->addForeignKey('workshop', 'm_workshop', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('tonnage', 'm_tonnage', 'id', '', 'RESTRICT');

        $this->forge->createTable('m_machine', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_machine', true);
    }
}
