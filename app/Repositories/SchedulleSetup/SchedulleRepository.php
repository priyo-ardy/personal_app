<?php

namespace App\Repositories\SchedulleSetup;

use App\Models\AppSetup\SchedulleSetup\SchedulleModel;
use App\Models\AppSetup\ShiftSetup\ShiftModel;
use App\Repositories\CrudRepository;

class SchedulleRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new SchedulleModel();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        // 1. Mengambil data utama dari database
        $data = $this->model->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        // PERBAIKAN: Pindahkan pemanggilan model ke luar loop 
        // agar tidak boros memori/performa server
        $shiftModel = new ShiftModel();

        // 2. Melakukan perulangan untuk memodifikasi setiap baris data
        foreach ($data as $key => $row) {

            // PERBAIKAN: Cek apakah data memuat kolom 'shift'. 
            // Ini mencegah error jika sewaktu-waktu fungsi ini dipanggil tanpa meminta kolom shift.
            if (isset($row['shift'])) {
                // Mengubah string JSON dari kolom 'shift' menjadi array PHP
                $shift_ids = json_decode($row['shift'], true);

                // Menyiapkan array kosong untuk menampung nama-nama shift di baris ini
                $nama_shift_array = [];

                // Memastikan $shift_ids tidak kosong dan benar-benar sebuah array
                if (is_array($shift_ids)) {
                    foreach ($shift_ids as $sh) {
                        // Mengambil data shift menggunakan model yang sudah disiapkan di luar loop
                        $detail_shift = $shiftModel->where('id', $sh)->first();

                        if (!empty($detail_shift) && isset($detail_shift->name)) {
                            $nama_shift_array[] = $detail_shift->name;
                        }
                    }
                }

                // 3. Menimpa data JSON asli dengan teks/nama shift yang sudah digabung
                // Karena kita langsung mengganti isi key 'shift', urutan untuk Excel akan tetap rapi.
                $data[$key]['shift'] = implode(', ', $nama_shift_array);
            }
        }

        // 4. Mengembalikan data yang sudah lengkap
        return $data;
    }
}
