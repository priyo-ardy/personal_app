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

        return base64_encode($encrypter->encrypt($value));
    }
}

if (!function_exists('dekripsi')) {
    function dekripsi($value)
    {
        $decrypter = service('encrypter');

        return $decrypter->decrypt(base64_decode($value));
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
            ]);
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
