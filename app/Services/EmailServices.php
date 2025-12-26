<?php

namespace App\Services;

use App\Repositories\EmailRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;


class EmailServices
{
    protected $emailRepo;
    protected $email;

    public function __construct()
    {
        $this->emailRepo = new EmailRepository();
        $this->email = Services::email();
    }

    public function registerQueue(string $toEmail, string $subject, string $body)
    {
        $register = $this->emailRepo->registerEmailQueue($toEmail, $subject, $body);
        if (!$register) {
            log_message('error', "Failed to register email queue {toEmail}", ['toEmail' => $toEmail, 'subject' => $subject, 'body' => $body]);
            throw new \Exception("Failed to register email queue", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }
}
