<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NbhxClassCategoryData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "bdc47a4c-a8dc-471f-8cb8-b292f84a55c0",
                "code" => "CNBHX-0001",
                "name" => "Indirect - Management",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:06:57+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:06:57+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "3e93a91a-10b3-41aa-bc20-9cc5561954e2",
                "code" => "CNBHX-0002",
                "name" => "Indirect - Business",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:07:04+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:07:04+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "33663bb1-46f8-4b4b-b0a0-e78153b06b6d",
                "code" => "CNBHX-0003",
                "name" => "Quasi - Production Assisting",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:07:11+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:07:11+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "971f93e7-be1b-4645-b503-a409e510e5a6",
                "code" => "CNBHX-0004",
                "name" => "Indirect - Administration",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:07:16+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:07:16+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "83b0eea1-5fb7-4c17-802c-1ebc3202d190",
                "code" => "CNBHX-0005",
                "name" => "Indirect - Technical",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:07:23+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:07:23+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "7fb7311a-1e52-43d1-9e81-94ef7e029d9d",
                "code" => "CNBHX-0006",
                "name" => "Direct - Production Worker",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:07:29+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:07:29+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "acb7ef6a-681e-4aa9-85b9-59f399495c3b",
                "code" => "CNBHX-0007",
                "name" => "Indirect - Supporting Service",
                "effective_date" => "2020-01-01",
                "description" => "",
                "created_at" => "2026-01-08 16:07:35+07",
                "created_by" => "0092",
                "updated_at" => "2026-01-08 16:07:35+07",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_class_nbhx')->insertBatch($data);
    }
}
