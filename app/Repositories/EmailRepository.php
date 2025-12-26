<?php

namespace App\Repositories;


use App\Interfaces\EmailInterface;
use App\Interfaces\CrudRepositoryInterface;
use App\Models\EmailQueueModel;

class EmailRepository implements EmailInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmailQueueModel();
    }

    public function registerEmailQueue(string $toEmail, string $subject, string $body)
    {
        $emailData = [
            'id' => generate_uuid(),
            'to_email' => $toEmail,
            'subject' => $subject,
            'body' => $body
        ];

        return $this->model->insert($emailData);
    }

    public function getEmailById(string $jobId)
    {
        return $this->model->where('id', $jobId)->first();
    }

    public function updateQueue(string $jobId, array $data)
    {
        return $this->model->update($jobId, $data);
    }

    public function getPendingJobs()
    {
        return $this->model->where('status', 'pending')->orWhere('status', 'failed')->orderBy('created_at', 'asc')->limit(25)->findAll();
    }

    public function updateJobStatus(string $jobId, string $status)
    {
        return $this->model->update($jobId, ['status' => $status]);
    }
}
