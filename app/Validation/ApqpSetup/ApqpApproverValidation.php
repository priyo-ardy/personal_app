<?php

namespace App\Validation\ApqpSetup;

class ApqpApproverValidation
{
    public static $save = [
        'approver_token' => [
            'label' => 'Apqp Token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Apqp Token is required'
            ]
        ],
        'approver.*' => [
            'label' => 'Approver',
            'rules' => 'required',
            'errors' => [
                'required' => 'Approver is required'
            ]
        ],
    ];
}
