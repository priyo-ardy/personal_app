<?php

namespace App\Validation\UoM;


class UomValidation
{
    public static $save = [
        'data_name' => [
            'label' => "UoM name",
            'rules' => 'required|min_length[1]|max_length[100]',
            'errors' => [
                'required' => "UoM name is required",
                'min_length' => "UoM name must be at least 1 characters long",
                'max_length' => "UoM name must be less than 100 characters long",
            ]
        ],
        'data_symbol' => [
            'label' => "UoM symbol",
            'rules' => 'required|min_length[1]|max_length[50]',
            'errors' => [
                'required' => "UoM symbol is required",
                'min_length' => "UoM symbol must be at least 1 characters long",
                'max_length' => "UoM symbol must be less than 50 characters long",
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'label' => "UoM token",
            'rules' => 'required',
            'errors' => [
                'required' => "UoM token is required",
            ]
        ],
        'data_name' => [
            'label' => "UoM name",
            'rules' => 'required|min_length[1]|max_length[100]',
            'errors' => [
                'required' => "UoM name is required",
                'min_length' => "UoM name must be at least 1 characters long",
                'max_length' => "UoM name must be less than 100 characters long",
            ]
        ],
        'data_symbol' => [
            'label' => "UoM symbol",
            'rules' => 'required|min_length[1]|max_length[50]',
            'errors' => [
                'required' => "UoM symbol is required",
                'min_length' => "UoM symbol must be at least 1 characters long",
                'max_length' => "UoM symbol must be less than 50 characters long",
            ]
        ],
    ];
}
