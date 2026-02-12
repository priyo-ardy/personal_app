<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TonnageData extends Seeder
{
    public function run()
    {
        $data = [
            [
                "id" => "f667f8ea-1ad5-4629-94c3-dca3c395dce6",
                "code" => "TNG-0001",
                "name" => "90T",
                "debugging" => "3.00",
                "description" => "",
                "created_at" => "2026-02-12 10:14:50+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:14:50+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "12ff2503-5525-4e0d-9e89-dfe955e09b7d",
                "code" => "TNG-0002",
                "name" => "160T",
                "debugging" => "5.00",
                "description" => "",
                "created_at" => "2026-02-12 10:15:14+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:15:14+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "7a3d6907-961e-4aa6-b4b7-1800f553a002",
                "code" => "TNG-0003",
                "name" => "250T",
                "debugging" => "6.00",
                "description" => "",
                "created_at" => "2026-02-12 10:15:38+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:15:38+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "b077f1bd-e983-4c0e-8f48-317301c06a91",
                "code" => "TNG-0004",
                "name" => "280T",
                "debugging" => "6.00",
                "description" => "",
                "created_at" => "2026-02-12 10:15:53+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:15:53+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "8339b6b9-3b91-46f5-9bba-8bff4491d9ab",
                "code" => "TNG-0005",
                "name" => "380T",
                "debugging" => "8.00",
                "description" => "",
                "created_at" => "2026-02-12 10:16:08+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:16:08+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "3cb7e699-06d9-40f9-a174-14e37903c492",
                "code" => "TNG-0006",
                "name" => "470T",
                "debugging" => "10.00",
                "description" => "",
                "created_at" => "2026-02-12 10:16:24+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:16:24+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "66347b64-8ff4-444e-8441-220317a0538a",
                "code" => "TNG-0007",
                "name" => "530T",
                "debugging" => "12.00",
                "description" => "",
                "created_at" => "2026-02-12 10:16:36+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:16:36+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "5b81e2f6-80a0-412b-a977-269ace35aa18",
                "code" => "TNG-0008",
                "name" => "600T",
                "debugging" => "15.00",
                "description" => "",
                "created_at" => "2026-02-12 10:16:53+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:16:53+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "e489149e-a350-458a-9cba-4dd6b50b8e7c",
                "code" => "TNG-0009",
                "name" => "800T",
                "debugging" => "15.00",
                "description" => "",
                "created_at" => "2026-02-12 10:17:02+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:17:02+07",
                "updated_by" => null,
                "deleted_at" => null
            ],
            [
                "id" => "5ea089f2-f415-4ddc-8c48-7f007a9b356a",
                "code" => "TNG-0010",
                "name" => "Sumitomo 180T",
                "debugging" => "5.00",
                "description" => "",
                "created_at" => "2026-02-12 10:17:12+07",
                "created_by" => "0092",
                "updated_at" => "2026-02-12 10:17:12+07",
                "updated_by" => null,
                "deleted_at" => null
            ]
        ];

        $this->db->table('m_tonnage')->insertBatch($data);
    }
}
