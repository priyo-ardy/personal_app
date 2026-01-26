<?php

namespace App\Validation\Factory;

class FactoryValidation
{
    public static $save = [
        'data_name' => [
            'label' => "Factory name",
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => "The {field} is required.",
                'min_length' => "The {field} must be at least {param} characters in length.",
                'max_length' => "The {field} cannot exceed {param} characters in length."
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => "Factory token",
            'rules' => 'required',
            'errors' => [
                'required' => "The {field} is required."
            ]
        ],
        'data_name' => [
            'label' => "Factory name",
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => "The {field} is required.",
                'min_length' => "The {field} must be at least {param} characters in length.",
                'max_length' => "The {field} cannot exceed {param} characters in length."
            ]
        ]
    ];
}
