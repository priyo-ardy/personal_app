<?php

namespace App\Traits;

use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

trait KalkulasiTrait
{
    public function kalkulasi_durasi_kontrak(string $tgl_awal, int $durasi, string $tipe_durasi)
    {
        if (empty($tgl_awal) || $durasi <= 0 || empty($tipe_durasi)) {
            throw new \Exception("The calculation parameters are invalid or incomplete.");
        }

        try {
            $date = new DateTime($tgl_awal);

            $unit = $this->mapTimeDurasi(strtolower($tipe_durasi));

            $interval = "+{$durasi} {$unit}";
            $date->modify($interval);

            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Gagal menghitung tanggal: " . $e->getMessage());
        }
    }

    private function mapTimeDurasi(string $tipe_durasi)
    {
        $map = [
            'hari' => 'days',
            'minggu' => 'weeks',
            'bulan' => 'months',
            'tahun' => 'years'
        ];

        return $map[$tipe_durasi] ?? 'days';
    }
}
