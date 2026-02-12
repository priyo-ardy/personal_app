<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MaterialCategoryData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "7bc0fede-7b6a-4f30-ba74-9da4115307bd",
                "code" => "MCTG-0001",
                "name" => "Finish Goods",
                "description" => "",
                "created_at" => "2026-02-12 10:18:01+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:01+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "51b90e6f-6bdd-45de-bd18-7f8aed1a3ea0",
                "code" => "MCTG-0002",
                "name" => "Semi Finished Goods",
                "description" => "",
                "created_at" => "2026-02-12 10:18:09+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:09+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "4acca041-58e0-49c2-a3f4-d64dc8273fe0",
                "code" => "MCTG-0003",
                "name" => "Sample",
                "description" => "",
                "created_at" => "2026-02-12 10:18:13+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:13+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "829b1a7d-046d-4f5b-8aee-2dcb9708e240",
                "code" => "MCTG-0004",
                "name" => "Raw Material",
                "description" => "",
                "created_at" => "2026-02-12 10:18:18+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:18+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "63760a3f-46ef-4f39-be7b-5e34fd22009b",
                "code" => "MCTG-0005",
                "name" => "Mixing Material",
                "description" => "",
                "created_at" => "2026-02-12 10:18:23+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:23+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "6e1c8966-9fc4-4318-96f7-24887655dda1",
                "code" => "MCTG-0006",
                "name" => "Child Parts",
                "description" => "",
                "created_at" => "2026-02-12 10:18:27+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:27+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "2217d1b8-5e6b-434e-9291-38035f56e8d4",
                "code" => "MCTG-0007",
                "name" => "Auxiliary",
                "description" => "",
                "created_at" => "2026-02-12 10:18:31+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:31+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "d30158a2-286b-4583-ab0c-ff2f29db43ac",
                "code" => "MCTG-0008",
                "name" => "Packaging",
                "description" => "",
                "created_at" => "2026-02-12 10:18:36+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:36+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "92938e84-c081-48af-8d67-3bdf290ec8ba",
                "code" => "MCTG-0009",
                "name" => "Spareparts",
                "description" => "",
                "created_at" => "2026-02-12 10:18:42+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:18:42+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "63a8185a-5294-4f0b-8049-c7a1e0ce3b7f",
                "code" => "MCTG-0010",
                "name" => "Fixed Asset",
                "description" => "",
                "created_at" => "2026-02-12 10:19:08+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:19:08+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "9b003c30-9b8f-4255-9a9b-8b5e86550e8b",
                "code" => "MCTG-0011",
                "name" => "Office Equipment",
                "description" => "",
                "created_at" => "2026-02-12 10:19:18+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:19:18+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "9586911e-8ec9-4891-94a6-74b04e145424",
                "code" => "MCTG-0012",
                "name" => "Office Supply",
                "description" => "",
                "created_at" => "2026-02-12 10:19:37+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:19:37+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "d3a0ad48-dcf3-47d4-a603-a44ffb916691",
                "code" => "MCTG-0013",
                "name" => "Equipment",
                "description" => "",
                "created_at" => "2026-02-12 10:19:44+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:19:44+07",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_material_category')->insertBatch($data);
    }
}
