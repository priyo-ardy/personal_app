<?php

namespace App\Validation\UniformSize;

class UniformSizeValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Uniform size name',
            'rules' => 'required|max_length[150]',
            'errors' => [
                'required' => 'Uniform size name is required',
                'max_length' => 'Uniform size name must be less than 150 characters'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Uniform size token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Uniform size token is required'
            ]
        ],
        'data_name' => [
            'label' => 'Uniform size name',
            'rules' => 'required|max_length[150]',
            'errors' => [
                'required' => 'Uniform size name is required',
                'max_length' => 'Uniform size name must be less than 150 characters'
            ]
        ]
    ];
}
