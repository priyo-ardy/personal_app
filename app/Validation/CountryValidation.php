<?php

namespace App\Validation;

class CountryValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Country Name',
            'rules' => 'required|min_length[2]|max_length[150]|is_unique[m_country.name]',
            'errors' => [
                'required' => '{field} is required.',
                'is_unique' => '{field} already exist.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
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
            'label' => 'Country Name',
            'rules' => 'required|min_length[2]|max_length[150]|',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} cannot exceed {param} characters in length.'
            ]
        ]
    ];
}
