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

    public function date_duration(string $start_date, string $end_date)
    {
        if (empty($start_date) || empty($end_date)) {
            throw new \Exception("The calculation parameters are invalid or incomplete.");
        }

        try {
            $date_1 = new DateTime($start_date);
            $date_2 = new DateTime($end_date);

            $interval = $date_1->diff($date_2);

            $result = [];

            if ($interval->y > 0) {
                $result[] = $interval->y . " Year" . ($interval->y > 1 ? "s" : "");
            }

            if ($interval->m > 0) {
                $result[] = $interval->m . " Month" . ($interval->m > 1 ? "s" : "");
            }

            $output = implode(", ", $result);

            if ($interval->d > 0) {
                $daysText = $interval->d . " Day" . ($interval->d > 1 ? "s" : "");

                if (!empty($output)) {
                    $output .= " and " . $daysText;
                } else {
                    $output = $daysText;
                }
            }

            return $output ?: "0 Days";
        } catch (\Exception $e) {
            throw new \Exception("Failed to calculate duration: " . $e->getMessage());
        }
    }
}
