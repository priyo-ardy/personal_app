<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KaryawanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => "VARCHAR",
                'constraint' => 50,
                'null' => false
            ],
            'category' => [
                'type' => "CHAR",
                'constraint' => 1,
                'null' => false,
                'default' => '0',
                'comment' => '0 = Karyawan, 1 = Karyawan support'
            ],
            'nik' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false
            ],
            'no_ktp' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'no_ktp_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'nama_karyawan' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'tempat_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'tgl_lahir' => [
                'type' => 'DATE',
                'null' => false
            ],
            'jenis_kelamin' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false
            ],
            'golongan_darah' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => true
            ],
            'status_karyawan' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => '1',
                'comment' => '1 = Aktif, 0 = Non Aktif'
            ],
            'agama' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
                'comment' => 'islam, kristen, katolik, budha, hindu, konghucu, other'
            ],
            'berat_badan' => [
                'type' => "NUMERIC",
                'constraint' => "3,2",
                'null' => false,
                'default' => 0
            ],
            'tinggi_badan' => [
                'type' => "NUMERIC",
                'constraint' => "3,2",
                'null' => false,
                'default' => 0
            ],
            'provinsi_saat_ini' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'kota_saat_ini' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'alamat_saat_ini' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'provinsi_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'kota_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'alamat_ktp' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'provinsi_orang_tua' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'kota_orang_tua' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'alamat_orang_tua' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'alamat_email' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'alamat_email_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'no_telepon' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'no_telepon_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'no_handphone' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'no_handphone_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'hitung_finger' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => '1',
                'comment' => '1 = Ya, 0 = Tidak'
            ],
            'hitung_lembur' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => '1',
                'comment' => '1 = Ya, 0 = Tidak'
            ],
            'tgl_masuk_kerja' => [
                'type' => 'DATE',
                'null' => false
            ],
            'tgl_keluar_kerja' => [
                'type' => 'DATE',
                'null' => true
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'default' => 'default.png'
            ],
            'nama_kontak_darurat' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false
            ],
            'relasi_kontak_darurat' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'tlp_kontak_darurat' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'tlp_kontak_darurat_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'alamat_kontak_darurat' => [
                'type' => 'TEXT',
                'null' => false
            ],
            'effective_date' => [
                'type' => 'DATE',
                'null' => false
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

        $this->forge->createTable('m_karyawan', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_karyawan', true);
    }
}
