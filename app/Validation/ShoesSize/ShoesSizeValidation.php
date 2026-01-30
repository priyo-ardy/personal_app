<?php

namespace App\Validation\ShoesSize;

class ShoesSizeValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Shoes size name',
            'rules' => 'required|max_length[150]',
            'errors' => [
                'required' => 'Shoes size name is required',
                'max_length' => 'Shoes size name must be less than 150 characters'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Shoes size token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Shoes size token is required'
            ]
        ],
        'data_name' => [
            'label' => 'Shoes size name',
            'rules' => 'required|max_length[150]',
            'errors' => [
                'required' => 'Shoes size name is required',
                'max_length' => 'Shoes size name must be less than 150 characters'
            ]
        ]
    ];
}
