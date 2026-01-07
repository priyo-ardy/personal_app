<?php

namespace App\Validation;

class SalaryRankValidation
{
    public static $save = [
        'data_code' => [
            'label' => "Salary rank code",
            'rules' => 'required|is_unique[m_salary_rank.code]|min_length[2]|max_length[15]|alpha_numeric_dash',
            'errors' => [
                'required' => '{field} is required.',
                'is_unique' => '{field} already exists.',
                'min_length' => '{field} must be at least {param} characters long.',
                'max_length' => '{field} must be no more than {param} characters long.',
                'alpha_numeric_dash' => '{field} must contain only letters, numbers, dashes, and underscores.'
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
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.'
            ]
        ],
        'data_salary_to' => [
            'label' => "To Salary",
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.'
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
        // 'data_code' => [
        //     'label' => "Salary rank code",
        //     'rules' => 'required|is_unique[m_salary_rank.code]|min_length[2]|max_length[15]|alpha_numeric_dash',
        //     'errors' => [
        //         'required' => '{field} is required.',
        //         'is_unique' => '{field} already exists.',
        //         'min_length' => '{field} must be at least {param} characters long.',
        //         'max_length' => '{field} must be no more than {param} characters long.',
        //         'alpha_numeric_dash' => '{field} must contain only letters, numbers, dashes, and underscores.'
        //     ]
        // ],
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
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.'
            ]
        ],
        'data_salary_to' => [
            'label' => "To Salary",
            'rules' => 'required|numeric',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.'
            ]
        ],
    ];
}
