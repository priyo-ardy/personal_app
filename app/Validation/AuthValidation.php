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
}
