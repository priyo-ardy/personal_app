<?php

namespace App\Services;

use App\Repositories\EmailRepository;
use App\Repositories\UserRepository;
use CodeIgniter\HTTP\ResponsableInterface;
use App\Validation\AuthValidation;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\EmailServices;
use Config\Services;
use Config\Database;

class ResetPasswordServices
{
    protected $emailRepo;
    protected $userRepo;
    protected $validasi;
    protected $db;
    protected $email;

    public function __construct(UserRepository $userRepo)
    {
        $this->emailRepo =  new EmailRepository();
        $this->userRepo = $userRepo;
        $this->validasi = Services::validation();
        $this->email = new EmailServices();
        $this->db = Database::connect();
    }

    public function resetPassword(array $data)
    {
        $this->validasi->setRules(AuthValidation::$emailRules);

        if ($this->validasi->run($data) === false) {
            $error_to_string = implode("<br>", $this->validasi->getErrors());
            log_message('error', "Validasi reset password gagal : {err}", ['err' => $error_to_string]);
            throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
        }

        $user_email = $data['user_email'];
        $hash = email_hash($user_email);

        $get_user_by_email = $this->userRepo->findByEmail($hash);
        if (!$get_user_by_email) {
            log_message('error', "User dengan email {email} tidak ditemukan", ['email' => $user_email]);
            throw new \Exception("Invalid email address", ResponseInterface::HTTP_BAD_REQUEST);
        }

        // Initialize password character
        $password_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $random_password = ""; // Create varible for generate random password;

        // Proses generate random password
        for ($i = 0; $i < 8; $i++) {
            $random_password .= $password_characters[random_int(0, strlen($password_characters) - 1)];
        }

        // Set password baru
        $new_password = password_hash($random_password, PASSWORD_DEFAULT);

        // Initialize data update
        $data = [
            'user_password' => $new_password,
            'updated_by' => session()->get('user_name')
        ];

        // Proses update password baru
        $this->db->transStart();
        $this->userRepo->update($get_user_by_email->user_id, $data);
        $this->db->transComplete();

        // Cek apakah update berhasil? jika tidak rollback proses update
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();

            log_message('error', "Failed to update new user password data {user}, " . $this->db->error(), ['user' => $get_user_by_email->user_name]);
            throw new \Exception("Request failed", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            return;
        }

        log_message("info", "Berhasil merubah user password {user} " . $this->db->getLastQuery(), ['user' => $get_user_by_email->user_name]);

        $data = [
            'full_name' => $get_user_by_email->full_name,
            'new_password' => $random_password,
            'date' => date("Y-m-d H:m:s"),
            'ip_address' => Services::request()->getIPAddress(),
            'user_agent' => Services::request()->getUserAgent()
        ];

        $subject = "Change password request";
        $body = view('Template/Email/reset_password', $data);

        // Jika update berhasil masukkan data email kedalam antrian
        $this->email->registerQueue($user_email, $subject, $body);

        return pesan(ResponseInterface::HTTP_OK, "A new password has been sent to your email address. Please check your inbox or spam folder.");
    }
}
