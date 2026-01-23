<?php

namespace App\Validation\CustomerCategory;

class CustomerCategoryValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Customer category name',
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required' => 'The {field} is required.',
                'min_length' => 'The {field} must be at least {param} characters in length.',
                'max_length' => 'The {field} cannot exceed {param} characters in length.'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Customer category token',
            'rules' => 'required',
            'errors' => [
                'required' => 'The {field} is required.'
            ]
        ],
        'data_name' => [
            'label' => 'Customer category name',
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required' => 'The {field} is required.',
                'min_length' => 'The {field} must be at least {param} characters in length.',
                'max_length' => 'The {field} cannot exceed {param} characters in length.'
            ]
        ]
    ];
}
