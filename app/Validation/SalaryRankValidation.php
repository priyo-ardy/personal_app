<?php

namespace App\Validation;

class SalaryRankValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Salary rank name",
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters long.',
                'max_length' => '{field} must be no more than {param} characters long.'
            ]
        ],
        'effective_date' => [
            'label' => "Effective date",
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => '{field} is required.',
                'valid_date' => '{field} must be a valid date.'
            ]
        ],
        'data_salary_from' => [
            'label' => "From Salary",
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.',
                'greater_than_equal_to' => '{field} must be greater than 0.'
            ]
        ],
        'data_salary_to' => [
            'label' => "To Salary",
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.',
                'greater_than_equal_to' => '{field} must be greater than 0.'
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'label' => "Token",
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.'
            ]
        ],
        'data_name' => [
            'label' => "Salary rank name",
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters long.',
                'max_length' => '{field} must be no more than {param} characters long.'
            ]
        ],
        'effective_date' => [
            'label' => "Effective date",
            'rules' => 'required|valid_date',
            'errors' => [
                'required' => '{field} is required.',
                'valid_date' => '{field} must be a valid date.'
            ]
        ],
        'data_salary_from' => [
            'label' => "From Salary",
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.',
                'greater_than_equal_to' => '{field} must be greater than 0.'
            ]
        ],
        'data_salary_to' => [
            'label' => "To Salary",
            'rules' => 'required|numeric|greater_than_equal_to[0]',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.',
                'greater_than_equal_to' => '{field} must be greater than 0.'
            ]
        ],
    ];
}
