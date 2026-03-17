<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DocumentFlowTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'parent_id' => [
                'type' => 'UUID',
                'null' => true
            ],
            'child_id' => [
                'type' => 'UUID',
                'null' => true
            ],
            'is_mandatory' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => true
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
        $this->forge->addKey('parent_id');
        $this->forge->addKey('child_id');

        $this->forge->addForeignKey('parent_id', 'm_apqp_document', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('child_id', 'm_apqp_document', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('m_document_flow', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_document_flow', true);
    }
}
