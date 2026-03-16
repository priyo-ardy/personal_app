<?php

namespace App\Traits;

use CodeIgniter\HTTP\ResponseInterface;
use App\Services\OvertimeSetup\OvertimeSetupService;
use App\Repositories\OvertimeSetup\OvertimeSetupRepository;
use CodeIgniter\I18n\Time;
use DateTime;

trait KalkulasiTrait
{
    protected $lembur;

    public function __construct()
    {
        $this->lembur = new OvertimeSetupService(new OvertimeSetupRepository());

        dd("Constructor berhasil jalan dan isi overtime adalah: ", $this->overtime);
    }

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

    public function kalkulasi_jam_kerja(string $jam_mulai, string $jam_selesai, int $break)
    {
        try {
            $start = new DateTime($jam_mulai);
            $end = new DateTime($jam_selesai);

            if ($end < $start) {
                $end->modify('+1 day');
            }

            $diff = $start->diff($end);
            // $totalMinutesGross = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
            $totalMinutes = ($diff->h * 60) + $diff->i;

            $netMinutes = $totalMinutes - $break;

            if ($netMinutes < 0) {
                $netMinutes = 0;
            }

            $decimalHour = round($netMinutes / 60, 2);

            return $decimalHour;
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    public function kalkulasi_overtime(string $overtime_type, string $overtime_start, string $overtime_finish, string $jam_kerja, int $min_ot, $rate = null)
    {
        try {
            if ($min_ot == '' || $min_ot == null) {
                $min_ot = 1;
            }

            $rates = json_decode($rate);


            if (!is_array($rates)) {
                throw new \Exception("Overtime rate is not a valid array", ResponseInterface::HTTP_BAD_REQUEST);
            }

            // log_message('info', '[KalkulasiTrait::kalkulasi_overtime] Rate for overtime type {type} is {rate}', ['type' => $overtime_type, 'rate' => $rates]);

            $result = [
                'durasi_kotor'    => 0.0,
                'lama_istirahat'   => 0.0,
                'lama_lembur'   => 0.0,
                'x15'             => 0.0,
                'x20'             => 0.0,
                'x30'             => 0.0,
                'x40'             => 0.0,
                'rate_lembur'    => 0.0,
                'status'          => 'success',
                'message'         => ''
            ];

            // 2. Hitung Durasi Kotor
            try {
                $start = new DateTime($overtime_start);
                $end = new DateTime($overtime_finish);
                if ($end < $start) $end->modify('+1 day');

                $diff = $start->diff($end);
                $totalMinutesGross = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                $result['durasi_kotor'] = round($totalMinutesGross / 60, 2);
            } catch (\Exception $e) {
                $result['status'] = 'error';
                return $result;
            }

            // 3. Validasi Batas Minimal & Maksimal
            if ($result['durasi_kotor'] < $min_ot) {
                $result['status'] = 'under_minimum';
                return $result;
            }
            if ($result['durasi_kotor'] > 14) {
                $result['status'] = 'exceeded_limit';
                return $result;
            }

            // 4. Logika Istirahat Berdasarkan Tipe Jam Kerja
            $istirahat = 0.0;
            $kotor = $result['durasi_kotor'];

            switch ($jam_kerja) {
                case 'PDK': // ISTIRAHAT PENDEK
                    if ($kotor > 5) {
                        $istirahat = 1.5;
                    } elseif ($kotor >= 3) {
                        $istirahat = 1.0;
                    }
                    break;

                case 'LBR': // ISTIRAHAT HARI LIBUR
                    if ($kotor >= 10) {
                        $istirahat = 1.5;
                    } elseif ($kotor >= 5) {
                        $istirahat = 1.0;
                    }
                    break;

                case 'REG': // ISTIRAHAT REGULER
                default:
                    if ($kotor >= 5) {
                        $istirahat = 1.0;
                    } elseif ($kotor >= 3) {
                        $istirahat = 0.5;
                    } elseif ($kotor >= 2) {
                        $istirahat = 0.25;
                    }
                    break;
            }

            $result['lama_istirahat'] = $istirahat;
            $result['lama_lembur'] = round($kotor - $istirahat, 2);

            // 5. Alokasi Rate Dinamis
            $remaining = $result['lama_lembur'];
            $index = 0;
            $lastRate = (float)end($rates);
            reset($rates);

            while ($remaining > 0) {
                $portion = ($remaining >= 1.0) ? 1.0 : $remaining;
                $currentRate = isset($rates[$index]) ? (float)$rates[$index] : $lastRate;

                if ($currentRate == 1.5) {
                    $result['x15'] += $portion;
                } elseif ($currentRate == 2.0) {
                    $result['x20'] += $portion;
                } elseif ($currentRate == 3.0) {
                    $result['x30'] += $portion;
                } elseif ($currentRate == 4.0) {
                    $result['x40'] += $portion;
                }

                $remaining -= $portion;
                $index++;
                if ($index > 24) break;
            }

            // 6. Total Lembur Akhir
            $result['rate_lembur'] = ($result['x15'] * 1.5) + ($result['x20'] * 2.0) +
                ($result['x30'] * 3.0) + ($result['x40'] * 4.0);

            // Pembulatan Response
            foreach (['x15', 'x20', 'x30', 'x40', 'rate_lembur', 'lama_lembur', 'durasi_kotor'] as $key) {
                $result[$key] = round($result[$key], 2);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[KalkulasiTrait::kalkulasi_lembur] Unexpected error occured : {err} from {ip} on line {line}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR'], 'line' => $e->getLine()]);
            throw new \Exception("Failed to calculate ovetime duration: " . $e->getMessage());
        }
    }

    public function kalkulasi_early_late_in_out($time)
    {
        try {
            $current_time = new Time($time);

            $early = $current_time->subHours(5);
            $late = $current_time->addHours(5);

            return [
                'early' => $early->toTimeString(),
                'late' => $late->toTimeString()
            ];
        } catch (\Exception $e) {
            throw new \Exception("Failed to calculate early/late in/out: " . $e->getMessage());
        }
    }

    public function getSchedulleDay($total_days)
    {
        try {
            $namaHari = [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
            ];

            $data = [];

            for ($i = 0; $i < $total_days; $i++) {
                $indexHari = $i % 7;
                $hariIni = $namaHari[$indexHari];
                $classLabel = $indexHari === 5 || $indexHari === 6 ? 'text-danger' : '';

                $data[] = $hariIni;
            }

            return $data;
        } catch (\Exception $e) {
            throw new \Exception("Failed to calculate early/late in/out: " . $e->getMessage());
        }
    }
}
