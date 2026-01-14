<?php

namespace App\Validation;

class CityValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'City Name',
            'rules' => 'required|min_length[2]|max_length[150]|is_unique[m_city.name]',
            'errors' => [
                'required' => 'City name is required',
                'is_unique' => 'City name already exists',
                'min_length' => 'City name must be at least 2 characters',
                'max_length' => 'City name must not exceed 150 characters'
            ]
        ],
        'data_province' => [
            'label' => 'Province',
            'rules' => 'required',
            'errors' => [
                'required' => 'Province is required'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Token is required'
            ]
        ],
        'data_name' => [
            'label' => 'City Name',
            'rules' => 'required|min_length[2]|max_length[150]',
            'errors' => [
                'required' => 'City name is required',
                'is_unique' => 'City name already exists',
                'min_length' => 'City name must be at least 2 characters',
                'max_length' => 'City name must not exceed 150 characters'
            ]
        ],
        'data_province' => [
            'label' => 'Province',
            'rules' => 'required',
            'errors' => [
                'required' => 'Province is required'
            ]
        ]
    ];
}
