<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use App\Repositories\EmailRepository;
use App\Jobs\SendEmailJob;
use CodeIgniter\CLI\CLI;

class SendEmailCommand extends BaseCommand
{
    protected $group = 'email';
    protected $name = 'email:pending';
    protected $description = "Processing pending email queue";

    public function run(array $params)
    {
        $emailRepo = new EmailRepository();
        $pendingJobs = $emailRepo->getPendingJobs();
        $gagal = 0;
        $sukses = 0;

        if (count($pendingJobs) == 0) {
            CLI::write("No pending email available");
            log_message("info", "No pending email available");
            return;
        }

        foreach ($pendingJobs as $job) {
            $sendEmailJob = new SendEmailJob();
            CLI::write("Processing job ID : {$job->id}\n");
            if ($sendEmailJob->execute($job->id)) {
                CLI::write("Email queue with ID: {$job->id} successfully sent\n");
                log_message('info', "Antrian email dengan ID {$job->id} berhasil dikirim");
                $sukses++;
            } else {
                CLI::write("Email queue with ID : {$job->id} failed to process\n");
                log_message('error', "Antrian email dengan ID {$job->id} gagal dikirim");
                $gagal++;
            }
        }
        CLI::write("ProseThe email queue delivery process is complete with a total of successfully delivered emails: $sukses and failed emails: $gagal out of a total queue of " . count($pendingJobs) . " emails.");
    }
}
