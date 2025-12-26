<?php

namespace App\Jobs;

use CodeIgniter\Email;
use App\Repositories\EmailRepository;

class SendEmailJob
{
    public function execute(string $jobId)
    {
        $emailRepo = new EmailRepository();
        $job = $emailRepo->getEmailById($jobId);

        if (!$job) {
            log_message('error', "No pending job available");
        }

        $email = \Config\Services::email();

        $email->initialize([
            'mailType' => 'html',
            'charset' => 'utf-8',
            'protocol' => 'smtp'
        ]);

        $email->setFrom('no-reply@schlemmer.co.id', "Schlemmer Personal Application");
        $email->setTo($job->to_email);
        $email->setSubject($job->subject);
        $email->setMessage($job->body);

        if ($email->send()) {
            $emailRepo->updateJobStatus($jobId, 'sent');
            log_message("info", "Successfully sent email to {email}", ['email' => $job->to_email]);
            return true;
        } else {
            $emailRepo->updateJobStatus($jobId, 'failed');
            $emailRepo->updateQueue($jobId, ['reason' => $email->printDebugger()]);
            log_message("error", "Failed to sent email to {email} \n\r" . $email->printDebugger(), ['email' => $job->to_email]);
        }
    }
}
