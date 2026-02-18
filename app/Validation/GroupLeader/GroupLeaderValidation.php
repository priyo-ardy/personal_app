<?php

namespace App\Validation\GroupLeader;

class  GroupLeaderValidation
{
    public static $save = [
        'data_employee' => [
            'label' => 'Employee',
            'rules' => 'required|is_unique[m_group_leader.employee_id]',
            'errors' => [
                'required' => 'Employee is required',
            ]
        ],
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Token',
            'rules' => 'required',
            'errors' => [
                'required' => 'Token is required',
            ]
        ],
        'data_employee' => [
            'label' => 'Employee',
            'rules' => 'required',
            'errors' => [
                'required' => 'Employee is required',
            ]
        ],
    ];
}
