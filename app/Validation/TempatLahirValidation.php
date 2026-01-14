<?php

namespace App\Validation;

class TempatLahirValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Birth place name',
            'rules' => 'required|min_length[2]|max_length[150]|is_unique[m_tempat_lahir.name]',
            'errors' => [
                'required' => 'The {field} is required',
                'is_unique' => 'The {field} must be unique',
                'min_length' => 'The {field} must be at least {param} characters',
                'max_length' => 'The {field} must be at most {param} characters'
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Birth place token',
            'rules' => 'required',
            'errors' => [
                'required' => 'The {field} is required'
            ]
        ],
        'data_name' => [
            'label' => 'Birth place name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'The {field} is required',
                'min_length' => 'The {field} must be at least {param} characters',
                'max_length' => 'The {field} must be at most {param} characters'
            ]
        ],
    ];
}
