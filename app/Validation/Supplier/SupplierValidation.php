<?php

namespace App\Validation\Supplier;


class SupplierValidation
{
    public static $save = [
        'data_name' => [
            'label' => 'Supplier Name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length'
            ]
        ],
        'data_email' => [
            'label' => 'Supplier Email',
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => '{field} is not valid'
            ]
        ],
        'data_phone' => [
            'label' => 'Supplier Phone',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ],
        'data_contact_person_email' => [
            'label' => 'Contact Person Email',
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => '{field} is not valid'
            ]
        ],
        'data_contact_person_phone' => [
            'label' => 'Contact Person Phone',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ],
        'data_npwp_no' => [
            'label' => 'Tax Registration No',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ],
        'data_bank_account_no' => [
            'label' => 'Bank Account No',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Supplier Token',
            'rules' => 'required',
            'errors' => [
                'required' => '{field} is required'
            ]
        ],
        'data_name' => [
            'label' => 'Supplier Name',
            'rules' => 'required|min_length[3]|max_length[150]',
            'errors' => [
                'required' => '{field} is required',
                'min_length' => '{field} must be at least {param} characters in length',
                'max_length' => '{field} must not exceed {param} characters in length'
            ]
        ],
        'data_email' => [
            'label' => 'Supplier Email',
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => '{field} is not valid'
            ]
        ],
        'data_phone' => [
            'label' => 'Supplier Phone',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ],
        'data_contact_person_email' => [
            'label' => 'Contact Person Email',
            'rules' => 'permit_empty|valid_email',
            'errors' => [
                'valid_email' => '{field} is not valid'
            ]
        ],
        'data_contact_person_phone' => [
            'label' => 'Contact Person Phone',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ],
        'data_npwp_no' => [
            'label' => 'Tax Registration No',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ],
        'data_bank_account_no' => [
            'label' => 'Bank Account No',
            'rules' => 'permit_empty|numeric',
            'errors' => [
                'numeric' => '{field} must be numeric'
            ]
        ]
    ];
}
