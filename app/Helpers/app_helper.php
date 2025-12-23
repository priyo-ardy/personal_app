<?php

use CodeIgniter\HTTP\ResponseInterface;

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
    function enkripsi(string $value): string
    {
        $encrypt = service('encrypt');

        return base64_encode($encrypt->encrypt($value)); //$encrypt->encrypt
    }
}

if (!function_exists('dekripsi')) {
    function dekripsi(string $value): string
    {
        $encrypt = service('encrypt');

        return $encrypt->decrypt(base64_decode($value)); //$encrypt->encrypt
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
