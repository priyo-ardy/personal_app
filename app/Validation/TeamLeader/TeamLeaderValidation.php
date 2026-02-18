<?php

namespace App\Validation\TeamLeader;

class TeamLeaderValidation
{
    public static $save = [
        'data_employee' => [
            'label' => 'Employee',
            'rules' => 'required|is_unique[m_team_leader.employee_id]',
            'errors' => [
                'is_unique' => 'This employee already has a team leader',
                'required' => '{field} is required.'
            ]
        ]
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Team leader token',
            'rules' => 'required',
            'errors' => ['required' => '{field} is required.']
        ],
        'data_employee' => [
            'label' => 'Employee',
            'rules' => 'required|is_unique[m_team_leader.employee_id]',
            'errors' => [
                'is_unique' => 'This employee already has a team leader',
                'required' => '{field} is required.'
            ]
        ]
    ];
}
