<?php

namespace App\Models\MasterData\Employee;

use App\Models\BaseModel;
use CodeIgniter\Model;

class EmployeeModel extends BaseModel
{
    protected $table            = 'm_karyawan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'category',
        'lokasi_kerja',
        'nik',
        'name',
        'no_ktp',
        'no_ktp_hash',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'golonga_darah',
        'agama',
        'tinggi_badan',
        'berat_badan',
        'alamat_email',
        'alamat_email_hash',
        'tlp_1',
        'tlp_1_hash',
        'tlp_2',
        'tlp_2_hash',
        'tgl_masuk_kerja',
        'provinsi_sekarang',
        'kota_sekarang',
        'alamat_sekarang',
        'provinsi_ktp',
        'kota_ktp',
        'alamat_ktp',
        'provinsi_orang_tua',
        'kota_orang_tua',
        'alamat_orang_tua',
        'photo',
        'bpjs_keshatan',
        'bpjs_kesehatan_hash',
        'bpjs_tenaga_kerja',
        'bpjs_tenaga_kerja_hash',
        'npwp',
        'npwp_hash',
        'no_rekening',
        'no_rekening_hash',
        'nama_bank',
        'nama_rekening',
        'relasi_kontak_darurat',
        'nama_kontak_darurat',
        'alamat_kontak_darurat',
        'tlp_kontak_darurat',
        'tlp_kontak_darurat_hash',
        'jenis_seragam',
        'ukuran_seragam',
        'ukuran_sepatu',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
