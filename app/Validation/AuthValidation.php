<?php

namespace App\Validation;

class AuthValidation
{
    public static $authRules = [
        'user_name' => [
            'label' => 'Username',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required',
            ]
        ],
        'user_password' => [
            'label' => 'Password',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required',
            ]
        ],
    ];

    public static $emailRules = [
        'user_email' => [
            'label' => "Email address",
            'rules' => 'required|valid_email|trim|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'valid_email' => '{field} invalid email address',
                'max_length' => '{field} cannot be more than {param} characters',
            ]
        ]
    ];
}
