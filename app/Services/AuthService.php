<?php

namespace app\Services;

use App\Repositories\UserRepository;
use App\Validation\AuthValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Validation;

class AuthService
{
    protected $userRepo;
    protected $validasi;

    /**
     * Constructor
     *
     * @param UserRepository $userRepo User repository instance
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo ?? new UserRepository();
        $this->validasi = Services::validation();
    }

    public function prosesLogin(array $data)
    {
        $this->validasi->setRules(AuthValidation::$authRules); //Lakukan validasi input user

        if ($this->validasi->run($data) === false) { // Jika validasi tidak memenuhi kriteria
            $error_to_string = implode("<br>", $this->validasi->getErrors()); // Buat pesan error menjadi string biasa
            log_message('error', "Validasi login gagal : {err}", ['err' => $error_to_string]); // simpan log
            throw new \Exception($error_to_string); // kembalikan error
        }

        $cek_user = $this->userRepo->findByUsername($data['username']); // Cek data user berdasarkan username ke repository UserRepository
        if (!$cek_user) {
            throw new \Exception("Invalid username or password", ResponseInterface::HTTP_UNAUTHORIZED); // Jika user tidak ditemukan kembalikan error
        }

        if ($cek_user->login_attempts >= 3) { // jika user salah memasukkan password lebih dari 3 kali kembalikan error user terkunci
            throw new \Exception("Your account is locked", ResponseInterface::HTTP_FORBIDDEN);
        }

        if ($cek_user->user_status == 'inactive') { // jika user tidak aktif kembalikan error
            throw new \Exception("Your account is inactive", ResponseInterface::HTTP_FORBIDDEN);
        }

        $verify = password_verify($data['password'], $cek_user->user_password); //Verify password
        if (!$verify) { //Jika password yang dimasukkan salah
            $this->userRepo->update($cek_user->user_id, ['login_attempts' => $cek_user->login_attempts + 1]); //Update login_attempts
            throw new \Exception("Invalid username or password", ResponseInterface::HTTP_UNAUTHORIZED); //Tampilkan pesan error
        }

        // Update user login data
        $this->userRepo->update($cek_user->user_id, [
            'last_login' => date('Y-m-d H:i:s'),
            'login_attempts' => 0,
            'login_from' => Services::request()->getIPAddress(),
        ]);

        log_message('info', "User {user} successfully login from {ip}", ['user' => $data['username'], 'ip' => $_SERVER['REMOTE_ADDR']]); // simpan log

        $session_data = [
            'logged' => true,
            'user_name' => $cek_user->user_name,
            'full_name' => $cek_user->full_name,
            'user_level' => $cek_user->user_level,
        ];

        session()->set($session_data);

        return true;
    }
}
