<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MaterialTable extends Migration
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
                'constraint' => 150,
                'null' => false
            ],
            'name' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false
            ],
            'specification' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true
            ],
            'category' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'cust_part_no' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true
            ],
            'cust_part_name' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true
            ],
            'color' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true
            ],
            'workshop' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'property' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true
            ],
            'uom' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'shift_capacity' => [
                'type' => 'NUMERIC',
                'constraint' => 11,
                'default' => 0
            ],
            'spq' => [
                'type' => 'NUMERIC',
                'constraint' => 11,
                'default' => 0
            ],
            'qty_per_bag' => [
                'type' => 'NUMERIC',
                'constraint' => 11,
                'default' => 0
            ],
            'net_weight' => [
                'type' => 'NUMERIC',
                'constraint' => '11,2',
                'default' => 0
            ],
            'gross_weight' => [
                'type' => 'NUMERIC',
                'constraint' => '11,2',
                'default' => 0
            ],
            'cavity' => [
                'type' => 'NUMERIC',
                'constraint' => 11,
                'default' => 0
            ],
            'mold_no' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true
            ],
            'process_route' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'image' => [
                'type' => "VARCHAR",
                'constraint' => 255,
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
        $this->forge->addForeignKey('category', 'm_material_category', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('workshop', 'm_workshop', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('uom', 'm_uom', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('process_route', 'm_route', 'id', '', 'RESTRICT');

        $this->forge->createTable('m_material', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_material', true);
    }
}
