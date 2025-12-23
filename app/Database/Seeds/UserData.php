<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserData extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => generate_uuid(),
                'user_name' => '0092',
                'full_name' => 'Ardy Priyo Sudiyantoko',
                'user_password' => password_hash('ardy9004', PASSWORD_DEFAULT),
                'user_email' => enkripsi('priyo.ardy@schlemmer.co.id'),
                'user_phone' => enkripsi('081210192858'),
                'email_hash' => email_hash('priyo.ardy@schlemmer.co.id'),
                'phone_hash' => phone_hash('081210192858'),
                'user_image' => 'default.png',
                'user_status' => 'active',
                'user_level' => 'superadmin',
                'login_attempts' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 'system',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'deleted_at' => null

            ]
        ];

        $this->db->table('m_users')->insertBatch($data);
    }
}
