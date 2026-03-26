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
            'stage_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'stage_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'document',
            ],
            'created_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'created_by' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => null
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => null
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->createTable('m_stages', true);

        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false
            ],
            'parent_id' => [
                'type' => 'UUID',
                'null' => true,
            ],
            'child_id' => [
                'type' => 'UUID',
                'null' => false,
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
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => null
            ],
            'updated_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ],
            'updated_by' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => null
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMPTZ',
                'null' => true,
                'default' => null
            ]
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addForeignKey('parent_id', 'm_stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('child_id', 'm_stages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('m_document_flow', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_document_flow', true);
        $this->forge->dropTable('m_stages', true);
    }
}
