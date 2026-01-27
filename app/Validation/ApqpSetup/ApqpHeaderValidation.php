<?php

namespace App\Validation\ApqpSetup;


class ApqpHeaderValidation
{
    public static $save = [
        'data_sequence' => [
            'label' => 'APQP Sequence',
            'rules' => 'required|numeric|is_unique[m_apqp_header.sequence]',
            'errors' => [
                'required' => '{field} is required.',
                'is_unique' => '{field} already exists.',
                'numeric' => '{field} must be numeric.'
            ]

        ],
        'data_name' => [
            'label' => 'APQP Name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least 3 characters long.',
                'max_length' => '{field} must be no more than 150 characters long.'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_name' => [
            'label' => 'APQP Name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least 3 characters long.',
                'max_length' => '{field} must be no more than 150 characters long.'
            ]
        ]
    ];
}
