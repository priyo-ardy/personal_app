<?php

namespace App\Validation\UniformType;

class UniformTypeValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Uniform type name',
            'rules' => 'required|max_length[150]',
            'errors' => [
                'required' => 'Uniform type name is required',
                'max_length' => 'Uniform type name must be less than 150 characters'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Uniform type token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Uniform type token is required'
            ]
        ],
        'data_name' => [
            'label' => 'Uniform type name',
            'rules' => 'required|max_length[150]',
            'errors' => [
                'required' => 'Uniform type name is required',
                'max_length' => 'Uniform type name must be less than 150 characters'
            ]
        ]
    ];
}
