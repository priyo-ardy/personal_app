<?php

namespace App\Interfaces;


interface EmailInterface
{
    public function registerEmailQueue(string $toEmail, string $subject, string $body);
    public function getEmailById(string $jobId);
    public function getPendingJobs();
    public function updateQueue(string $jobId, array $data);
    public function updateJobStatus(string $jobId, string $status);
}
