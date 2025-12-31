<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmailQueueTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'to_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'status' => [
                // 'type' => 'ENUM',
                // 'constraint' => ['pending', 'sent', 'failed'],
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'pending',
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ]
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('status');

        $this->forge->createTable('q_email_queue', true);
    }

    public function down()
    {
        $this->forge->dropTable('q_email_queue', true);
    }
}
