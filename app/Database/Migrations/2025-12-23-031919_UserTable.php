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
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'user_name' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'full_name' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'user_password' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => false,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'user_email' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'user_phone' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'email_hash' => [
                'type' => "VARCHAR",
                'constraint' => 100,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'phone_hash' => [
                'type' => "VARCHAR",
                'constraint' => 150,
                'null' => true,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'user_image' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'null' => false,
                'default' => 'default.png',
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'user_status' => [
                'type' => "ENUM",
                'constraint' => "'active', 'inactive'",
                'null' => false,
                'default' => 'active',
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'user_level' => [
                'type' => "ENUM",
                'constraint' => "'superadmin', 'manager', 'supervisor', 'leader', 'admin', 'user'",
                'null' => false,
                'default' => 'user',
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'login_attempts' => [
                'type' => "INT",
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'last_login' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'login_from' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'created_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'created_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'updated_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'updated_by' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ],
            'deleted_at' => [
                'type' => "DATETIME",
                'null' => true,
                'default' => null,
                'charset' => 'utf8',
                'collation' => 'utf8mb4_uca1400_ai_ci'
            ]
        ]);

        $this->forge->addKey(['user_id', 'user_name', 'email_hash', 'phone_hash'], true, true);
        $this->forge->createTable('m_users', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_users', true);
    }
}
