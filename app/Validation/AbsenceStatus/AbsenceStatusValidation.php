<?php

namespace App\Validation\AbsenceStatus;

class AbsenceStatusValidation
{
    public static $save = [
        'data_code' => [
            'label' => 'Absence status code',
            'rules' => 'trim|required|min_length[1]|max_length[10]|is_unique[m_absence_status.code]',
            'errors' => [
                'is_unique' => 'Absence status code already exist. Please use another code.',
                'required' => 'Absence status code is required.',
                'min_length' => 'Absence status code must be at least 1 character.',
                'max_length' => 'Absence status code must not exceed 10 characters.'
            ]
        ],
        'data_name' => [
            'label' => 'Absence status name',
            'rules' => 'trim|required|min_length[1]|max_length[150]',
            'errors' => [
                'required' => 'Absence status name is required.',
                'min_length' => 'Absence status name must be at least 1 character.',
                'max_length' => 'Absence status name must not exceed 150 characters.'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Data token',
            'rules' => 'trim|required',
            'errors' => [
                'required' => 'Data token is required.'
            ]
        ],
        'data_code' => [
            'label' => 'Absence status code',
            'rules' => 'trim|required|min_length[1]|max_length[10]',
            'errors' => [
                'required' => 'Absence status code is required.',
                'min_length' => 'Absence status code must be at least 1 character.',
                'max_length' => 'Absence status code must not exceed 10 characters.'
            ]
        ],
        'data_name' => [
            'label' => 'Absence status name',
            'rules' => 'trim|required|min_length[1]|max_length[150]',
            'errors' => [
                'required' => 'Absence status name is required.',
                'min_length' => 'Absence status name must be at least 1 character.',
                'max_length' => 'Absence status name must not exceed 150 characters.'
            ]
        ]
    ];
}
