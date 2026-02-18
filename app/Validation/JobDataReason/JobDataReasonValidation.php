<?php

namespace App\Validation\JobDataReason;

class JobDataReasonValidation
{
    public static $save = [
        'data_action' => [
            'label' => 'Job data action',
            'rules' => 'required',
            'errors' => ['required' => '{field} is required.']
        ],
        'data_name' => [
            'label' => 'Job data reason name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least 3 characters',
                'max_length' => '{field} must be less than 150 characters'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Job data token',
            'rules' => 'required',
            'errors' => ['required' => '{field} is required.']
        ],
        'data_action' => [
            'label' => 'Job data action',
            'rules' => 'required',
            'errors' => ['required' => '{field} is required.']
        ],
        'data_name' => [
            'label' => 'Job data reason name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least 3 characters',
                'max_length' => '{field} must be less than 150 characters'
            ]
        ]
    ];
}
