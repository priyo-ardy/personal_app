<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmpCategoryData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "a828da5b-7ef5-4237-83d4-89e6ca93df13",
                "code" => "ECT0001",
                "name" => "Indirect",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:02:22+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:02:22+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "22965550-ff3c-455a-91a0-936fb79eb0e9",
                "code" => "ECT0002",
                "name" => "Quasy",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:02:30+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:02:30+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "d86bdae2-b9c6-45ca-8d06-9b4fc3981a66",
                "code" => "ECT0003",
                "name" => "Direct",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:02:35+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:02:35+07",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_employee_category')->insertBatch($data);
    }
}
