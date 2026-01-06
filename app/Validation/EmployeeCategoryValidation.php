<?php

namespace App\Validation;

class EmployeeCategoryValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters long',
                'max_length' => '{field} must not exceed {param} characters long',
            ]
        ],
        'effective_date' => [
            'label' => 'Effective Date',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => '{field} is required',
                'valid_date' => '{field} must be a valid date',
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required',
            ]
        ],
        'data_name' => [
            'label' => 'Name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters long',
                'max_length' => '{field} must not exceed {param} characters long',
            ]
        ],
        'effective_date' => [
            'label' => 'Effective Date',
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => '{field} is required',
                'valid_date' => '{field} must be a valid date',
            ]
        ]
    ];
}
