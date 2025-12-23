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
