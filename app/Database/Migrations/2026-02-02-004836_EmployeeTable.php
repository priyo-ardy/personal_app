<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EmployeeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'UUID',
                'null' => false,
            ],
            'category' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'default' => '0',
                'comment' => '0 = Regular Employee, 2 = Supporting Employee',
                'null' => false
            ],
            'lokasi_kerja' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'nik' => [
                'type' => 'CHAR',
                'constraint' => '5',
                'null' => false,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'no_ktp' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'no_ktp_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => false,
            ],
            'tempat_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'tgl_lahir' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jenis_kelamin' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'comment' => 'L = Laki-laki, P = Perempuan',
            ],
            'golongan_darah' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => true,
            ],
            'agama' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
                'comment' => 'Islam, Kristen, Katolik, Hindu, Budha, Lainnya',
            ],
            'tinggi_badan' => [
                'type' => 'NUMERIC',
                'constraint' => '3,2',
                'null' => true,
                'default' => 0
            ],
            'berat_badan' => [
                'type' => 'NUMERIC',
                'constraint' => '3,2',
                'null' => true,
                'default' => 0
            ],
            'alamat_email' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'alamat_email_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => false,
            ],
            'tlp_1' => [
                'type' => "TEXT",
                'null' => false,
            ],
            'tlp_1_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => false,
            ],
            'tlp_2' => [
                'type' => "TEXT",
                'null' => true,
            ],
            'tlp_2_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => true,
            ],
            'tgl_masuk_kerja' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'provinsi_sekarang' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'kota_sekarang' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'alamat_sekarang' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'provinsi_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'kota_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'alamat_ktp' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'provinsi_orang_tua' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'kota_orang_tua' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'alamat_orang_tua' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'bpjs_keshatan' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'bpjs_kesehatan_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => true
            ],
            'bpjs_tenaga_kerja' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'bpjs_tenaga_kerja_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => true
            ],
            'npwp' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'npwp_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => true
            ],
            'no_rekening' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'no_rekening_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => true
            ],
            'nama_bank' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true
            ],
            'nama_rekening' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true
            ],
            'relasi_kontak_darurat' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'nama_kontak_darurat' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true
            ],
            'alamat_kontak_darurat' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true
            ],
            'tlp_kontak_darurat' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'tlp_kontak_darurat_hash' => [
                'type' => 'CHAR',
                'constraint' => 64,
                'null' => true
            ],
            'jenis_seragam' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'ukuran_seragam' => [
                'type' => 'UUID',
                'null' => false
            ],
            'ukuran_sepatu' => [
                'type' => 'UUID',
                'null' => false
            ],
            'aksesoris' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => '1. Topi Navy, 2. Topi Kuning, 3. Hijab'
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

        $this->forge->addKey('id', true, true);
        $this->forge->addKey('nik', false, true);
        $this->forge->addForeignKey('lokasi_kerja', 'm_location', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('tempat_lahir', 'm_tempat_lahir', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('provinsi_sekarang', 'm_province', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('kota_sekarang', 'm_city', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('provinsi_ktp', 'm_province', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('kota_ktp', 'm_city', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('provinsi_orang_tua', 'm_province', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('kota_orang_tua', 'm_city', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('relasi_kontak_darurat', 'm_family_relation', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('jenis_seragam', 'm_uniform_type', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('ukuran_seragam', 'm_uniform_size', 'id', '', 'RESTRICT');
        $this->forge->addForeignKey('ukuran_sepatu', 'm_shoes_size', 'id', '', 'RESTRICT');

        $this->forge->createTable('m_karyawan', true);
    }

    public function down()
    {
        $this->forge->dropTable('m_karyawan', true);
    }
}
