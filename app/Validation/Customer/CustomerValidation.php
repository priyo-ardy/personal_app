<?php

namespace App\Validation\Customer;


class CustomerValidation
{
    public static $save = [
        'data_category' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Customer category is required'
            ]
        ],
        'data_name' => [
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'Customer name is required',
                'min_length' => 'Customer name must be at least 3 characters',
                'max_length' => 'Customer name must not exceed 150 characters'
            ]
        ],
        'data_email' => [
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => 'The {field} is not valid email format'
            ]
        ],
        'data_phone' => [
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => 'The {field} is must be a number'
            ]
        ],
        'email_contact' => [
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => 'The {field} is not valid email format'
            ]
        ],
        'phone_contact' => [
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => 'The {field} is must be a number'
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Customer token is required'
            ]
        ],
        'data_category' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Customer category is required'
            ]
        ],
        'data_name' => [
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => 'Customer name is required',
                'min_length' => 'Customer name must be at least 3 characters',
                'max_length' => 'Customer name must not exceed 150 characters'
            ]
        ],
        'data_email' => [
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => 'Customer email address is not valid'
            ]
        ],
        'data_phone' => [
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => 'Customer phone number is not valid'
            ]
        ],
        'email_contact' => [
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => 'Contact person email address is not valid'
            ]
        ],
        'phone_contact' => [
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => 'Contact person phone number is not valid'
            ]
        ],
    ];
}
