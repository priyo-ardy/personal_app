<?php

namespace App\Validation;

class ProvinceValidation
{
    public static $save = [
        'data_country' => [
            'label' => 'Country',
            'rules' => 'required',
            'errors' => [
                'required' => 'Country is required',
            ]
        ],
        'data_name' => [
            'label' => 'Province name',
            'rules' => 'required|is_unique[m_province.name]|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'Province name is required',
                'is_unique' => 'Province name already exist',
                'min_length' => 'Province name must be at least 2 characters',
                'max_length' => 'Province name must not exceed 150 characters',
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Token is required',
            ]
        ],
        'data_country' => [
            'label' => 'Country',
            'rules' => 'required',
            'errors' => [
                'required' => 'Country is required',
            ]
        ],
        'data_name' => [
            'label' => 'Province name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'Province name is required',
                'min_length' => 'Province name must be at least 2 characters',
                'max_length' => 'Province name must not exceed 150 characters',
            ]
        ],
    ];
}
