<?php

use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

if (!function_exists('generate_uuid')) {
    function generate_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}

if (!function_exists('enkripsi')) {
    function enkripsi($value)
    {
        $encrypter = service('encrypter');

        return bin2hex(base64_encode($encrypter->encrypt($value)));
    }
}

if (!function_exists('dekripsi')) {
    function dekripsi($value)
    {
        $decrypter = service('encrypter');

        return $decrypter->decrypt(base64_decode(hex2bin($value)));
    }
}

if (!function_exists('pesan')) {
    function pesan(string $error_code, string $message, $data =  null)
    {
        $response = service('response');
        return $response
            ->setStatusCode($error_code)
            ->setJSON([
                'status' => $error_code,
                'message' => $message,
                'data' => $data
            ], JSON_PRETTY_PRINT);
    }
}

if (!function_exists('email_hash')) {
    function email_hash(string $email_address)
    {
        $secret_key = getenv('email_salt') ? getenv('email_salt') : '#@3m4!lXx';
        return hash('sha256', $secret_key . $email_address);
    }
}

if (!function_exists('phone_hash')) {
    function phone_hash(string $phone_number)
    {
        $secret_key = getenv('phone_salt') ? getenv('phone_salt') : '*#Ph0n3!!#*';
        return hash('sha256', $secret_key . $phone_number);
    }
}

if (!function_exists('npwp_hash')) {
    function npwp_hash(string $npwp)
    {
        $secret_key = getenv('npwp_salt') ? getenv('npwp_salt') : '*#NpWp!!#*';
        return hash('sha256', $secret_key . $npwp);
    }
}

if (!function_exists('bank_account_no_hash')) {
    function bank_account_no_hash(string $bank_account_no)
    {
        $secret_key = getenv('bank_account_no_salt') ? getenv('bank_account_no_salt') : '*#BankAccNo!!#*';
        return hash('sha256', $secret_key . $bank_account_no);
    }
}

if (!function_exists('sensor_email')) {
    /**
     * Custom Masking Email sesuai Request
     * * Logika Nama:
     * - admin.customer -> a****.c*******
     * - budi -> b*** (fallback jika tidak ada titik)
     * * Logika Domain:
     * - datacom.co.id -> *******.co.id
     * - gmail.com -> *****.com
     */
    function sensor_email(string $email): string
    {
        // 1. Validasi dasar
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        // 2. Pisahkan Nama dan Domain
        [$local, $fullDomain] = explode('@', $email);

        // --- PROSES BAGIAN NAMA (LOCAL PART) ---
        // Cek apakah ada titik (.)
        if (strpos($local, '.') !== false) {
            // Pecah berdasarkan titik (misal: admin, customer)
            $parts = explode('.', $local);
            $maskedParts = [];

            foreach ($parts as $part) {
                $len = strlen($part);
                if ($len > 0) {
                    // Ambil huruf pertama, sisanya bintang
                    $maskedParts[] = substr($part, 0, 1) . str_repeat('*', $len - 1);
                } else {
                    $maskedParts[] = '';
                }
            }
            // Gabungkan kembali dengan titik
            $finalLocal = implode('.', $maskedParts);
        } else {
            // Fallback jika nama tidak punya titik (misal: "admin")
            // Tetap ambil huruf pertama, sisanya bintang
            $len = strlen($local);
            $finalLocal = substr($local, 0, 1) . str_repeat('*', $len - 1);
        }

        // --- PROSES BAGIAN DOMAIN ---
        // Kita asumsikan bagian pertama setelah @ adalah nama perusahaan/provider
        // dan sisanya adalah TLD (.com, .co.id, dll)

        // Limit=2 artinya kita hanya memecah pada titik PERTAMA.
        // Contoh: datacom.co.id -> [0] = datacom, [1] = co.id
        $domainParts = explode('.', $fullDomain, 2);

        if (count($domainParts) == 2) {
            $domainName = $domainParts[0]; // datacom
            $domainExt  = $domainParts[1]; // co.id

            // Sensor total nama domainnya
            $maskedDomain = str_repeat('*', strlen($domainName)) . '.' . $domainExt;
        } else {
            // Fallback jika domain tidak punya titik (jarang terjadi di email valid, misal localhost)
            $maskedDomain = $fullDomain;
        }

        return $finalLocal . '@' . $maskedDomain;
    }
}

if (!function_exists('sensor_phone')) {
    /**
     * Masking Phone Number
     * Standard: 081234567890 -> 0812****7890
     */
    function sensor_phone(string $phone, string $maskChar = '*'): string
    {
        // Bersihkan input selain angka
        $cleanPhone = preg_replace('/\D/', '', $phone);
        $len = strlen($cleanPhone);

        // Jika terlalu pendek (misal extension), jangan di mask atau return as is
        if ($len < 8) {
            return $phone;
        }

        // Konfigurasi Standar
        $showStart = 4; // Tampilkan 4 digit awal (misal 0812)
        $showEnd   = 3; // Tampilkan 3 digit akhir

        $maskLen = $len - ($showStart + $showEnd);

        // Safety check jika panjang negatif
        if ($maskLen < 1) {
            return $cleanPhone;
        }

        $startPart = substr($cleanPhone, 0, $showStart);
        $endPart   = substr($cleanPhone, -$showEnd);
        $maskedPart = str_repeat($maskChar, 4); // Fixed 4 bintang agar rapi, atau gunakan $maskLen untuk dynamic

        return $startPart . $maskedPart . $endPart;
    }
}

if (!function_exists('updateEnv')) {
    /**
     * Update file .env
     *
     * @param array $data Array Key => Value yang ingin diupdate
     * @return bool
     */
    function updateEnv(array $data)
    {
        $path = ROOTPATH . '.env';

        if (!file_exists($path)) {
            // Jika tidak ada .env, coba cek .env.example atau buat baru
            return false;
        }

        // Baca semua isi file .env sebagai string
        $content = file_get_contents($path);

        foreach ($data as $key => $value) {
            // Sanitasi input: Hapus spasi berlebih
            $value = trim($value);

            // Jika value mengandung spasi, apit dengan tanda kutip
            if (strpos($value, ' ') !== false) {
                $value = '"' . $value . '"';
            }

            // Regex untuk mencari baris: KEY=lama
            // Penjelasan Regex:
            // ^        : Awal baris
            // \s* : Spasi opsional
            // $key     : Nama variable (misal email.SMTPHost)
            // \s* : Spasi opsional
            // =        : Tanda sama dengan
            // .* : Apapun isinya sampai akhir baris
            $pattern = "/^" . preg_quote($key, '/') . "\s*=.*$/m";

            if (preg_match($pattern, $content)) {
                // Jika Key ditemukan, GANTI baris tersebut
                $content = preg_replace($pattern, "{$key}={$value}", $content);
            } else {
                // Jika Key TIDAK ditemukan, TAMBAHKAN di baris paling bawah
                $content .= PHP_EOL . "{$key}={$value}";
            }
        }

        // Tulis kembali ke file
        return file_put_contents($path, $content) !== false;
    }
}
