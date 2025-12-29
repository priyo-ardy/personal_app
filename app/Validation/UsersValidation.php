<?php

namespace App\Validation;

class UsersValidation
{
    public static $saveUser = [
        'data_username' => [
            'label' => 'Username',
            'rules' => 'required|is_unique[m_users.user_name]|min_length[3]|max_length[20]',
            'errors' => [
                'required' => '{field} is required.',
                'is_unique' => '{field} {value} already exists. Please choose another username.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_fullname' => [
            'label' => 'Full Name',
            'rules' => 'required|min_length[5]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_email' => [
            'label' => 'Email Address',
            'rules' => 'required|valid_email|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'valid_email' => '{field} must be a valid email address.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_phone' => [
            'label' => 'Phone Number',
            'rules' => 'required|numeric|max_length[20]',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[8]|max_length[20]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_level' => [
            'label' => 'User Level',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.',
            ]
        ],
    ];

    public static $updateUser = [
        'data_token' => [
            'label' => 'User token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.',
            ]
        ],
        'data_username' => [
            'label' => 'Username',
            'rules' => 'required|is_unique[users.username]|min_length[5]|max_length[20]',
            'errors' => [
                'required' => '{field} is required.',
                'is_unique' => '{field} {value} already exists. Please choose another username.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_fullname' => [
            'label' => 'Full Name',
            'rules' => 'required|min_length[5]|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_email' => [
            'label' => 'Email Address',
            'rules' => 'required|valid_email|max_length[150]',
            'errors' => [
                'required' => '{field} is required.',
                'valid_email' => '{field} must be a valid email address.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_phone' => [
            'label' => 'Phone Number',
            'rules' => 'required|numeric|max_length[20]',
            'errors' => [
                'required' => '{field} is required.',
                'numeric' => '{field} must be a number.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[8]|max_length[20]',
            'errors' => [
                'required' => '{field} is required.',
                'min_length' => '{field} must be at least {param} characters in length.',
                'max_length' => '{field} must not exceed {param} characters in length.',
            ]
        ],
        'data_level' => [
            'label' => 'User Level',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required.',
            ]
        ],
    ];
}
