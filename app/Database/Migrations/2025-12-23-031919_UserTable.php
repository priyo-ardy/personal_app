<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
            ],
            'user_name' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
            ],
            'full_name' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false,
            ],
            'user_password' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => false,
            ],
            'user_email' => [
                'type' => "TEXT",
                'null' => true,
            ],
            'user_phone' => [
                'type' => "TEXT",
                'null' => true,
            ],
            'email_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true,
            ],
            'phone_hash' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => true,
            ],
            'user_image' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => false,
                'default' => 'default.png',
            ],
            'user_status' => [
                // 'type' => "ENUM",
                // 'constraint' => "'active', 'inactive'",
                'type' => "VARCHAR",
                'constraint' => 20,
                'null' => false,
                'default' => 'active',
            ],
            'user_level' => [
                // 'type' => "ENUM", 
                // 'constraint' => "'superadmin', 'manager', 'supervisor', 'leader', 'admin', 'user'",
                'type' => "VARCHAR",
                'constraint' => 20,
                'null' => false,
                'default' => 'user',
            ],
            'login_attempts' => [
                'type' => "INT",
                // 'constraint' => 11,
                'null' => false,
                'default' => 0,
            ],
            'last_login' => [
                'type' => "TIMESTAMP",
                'null' => true,
                'default' => null,
            ],
            'login_from' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => null,
            ],
            'remark' => [
                'type' => "TEXT",
                'null' => true,
                'default' => null,
            ],
            'created_at' => [
                'type' => "TIMESTAMP",
                'null' => true,
                'default' => null,
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => null,
            ],
            'updated_at' => [
                'type' => "TIMESTAMP",
                'null' => true,
                'default' => null,
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => null,
            ],
            'deleted_at' => [
                'type' => "TIMESTAMP",
                'null' => true,
                'default' => null,
            ]
        ]);

        $this->forge->addKey('user_id', true, true);
        $this->forge->addKey('user_name', false, true);
        $this->forge->addKey('email_hash', false, true);
        $this->forge->addKey('phone_hash', false, true);

        $this->forge->createTable('m_users', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_users', true);
    }
}
