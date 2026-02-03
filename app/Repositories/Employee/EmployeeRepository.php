<?php

namespace App\Repositories\Employee;

use App\Models\MasterData\Employee\EmployeeModel;

use App\Repositories\CrudRepository;

class EmployeeRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new EmployeeModel();
    }

    public function getNewNik(string $category)
    {
        // 1. Cari data terakhir yang diawali dengan category tersebut
        // Menggunakan LIKE agar lebih akurat mencari prefix
        $lastData = $this->model->where("nik LIKE '$category%'")
            ->orderBy('nik', 'DESC')
            ->limit(1)
            ->first();

        if ($lastData) {
            $lastCodeString = $lastData->nik;

            // 2. Ambil angka setelah karakter category
            // Contoh: Jika nik = "A0005" dan category = "A", ambil mulai dari indeks 1
            $lastNumberStr = substr($lastCodeString, strlen($category));

            // 3. Pastikan dikonversi ke integer sebelum ditambah 1
            $nextNumber = (int)$lastNumberStr + 1;
        } else {
            // Jika belum ada data dengan category tersebut
            $nextNumber = 1;
        }

        // 4. Gabungkan kembali dengan padding 4 digit
        $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $category . $paddedNumber;
    }

    public function checkKtp(string $no_ktp_hash)
    {
        return $this->model->where('no_ktp_hash', $no_ktp_hash)->first();
    }

    public function checkEmail(string $email_hash)
    {
        return $this->model->where('alamat_email_hash', $email_hash)->first();
    }

    public function checkPhone(string $phone_hash)
    {
        return $this->model->where('tlp_1_hash', $phone_hash)->first();
    }

    public function checkBpjsKesehatan(string $bpjs_keshatan_hash)
    {
        return $this->model->where('bpjs_kesehatan_hash', $bpjs_keshatan_hash)->first();
    }

    public function checkBpsjTenagaKerja(string $bpjs_tk_hash)
    {
        return $this->model->where('bpjs_tenaga_kerja_hash', $bpjs_tk_hash)->first();
    }

    public function checkNpwp(string $npwp_hash)
    {
        return $this->model->where('npwp_hash', $npwp_hash)->first();
    }

    public function checkRekening(string $rekening_hash)
    {
        return $this->model->where('no_rekening_hash', $rekening_hash)->first();
    }
}
