<?php

namespace App\Controllers\SiteSetting;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SiteSettingController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Site Setting',
            'footer' => [
                '<script src="' . base_url() . '/js/SiteSetting/site.js"></script>'
            ]
        ];

        return view('SiteSetting/index', $data);
    }

    public function saveData()
    {
        // 1. Validasi Input (Sangat Penting!)
        if (!$this->validate([
            'email_host' => 'required',
            'email_user' => 'required|valid_email',
            'email_pass' => 'required',
            'email_port' => 'required|integer',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Siapkan data sesuai KEY di .env kamu
        // Key kiri harus PERSIS sama dengan yang ada di file .env
        $newData = [
            'email.SMTPHost' => $this->request->getPost('email_host'),
            'email.SMTPUser' => $this->request->getPost('email_user'),
            'email.SMTPPass' => $this->request->getPost('email_pass'),
            'email.SMTPPort' => $this->request->getPost('email_port'),
            'email.fromEmail' => $this->request->getPost('email_user'), // Biasanya sama dengan user
        ];

        // 3. Panggil Helper untuk update
        if (updateEnv($newData)) {
            return redirect()->back()->with('message', 'Konfigurasi Email berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Gagal menulis ke file .env. Cek permission!');
        }
    }
}
