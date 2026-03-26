<?php

namespace App\Validation\DocumentFlow;


class DocumentFlowValidation
{
    public static $save = [
        'child_id' => [
            'label' => 'Document child',
            'rules' => 'required|is_unique[m_document_flow.child_id]',
            'errors' => [
                'required' => 'Document child is required',
                'is_unique' => 'Document child already exists'
            ]
        ],
        'parent_id' => 'permit_empty',
    ];

    public static $update = [
        'data_token' => [
            'label' => 'Document flow token',
            'rules' => 'required',
            'errors' => ['required' => 'Document flow token is required']
        ],
        'data_level' => [
            'label' => 'Document flow level',
            'rules' => 'required|numeric',
            'errors' => [
                'required' => 'Document flow level is required',
                'numeric' => 'Document flow level must be numeric'
            ]
        ],
        'data_parent' => [
            'label' => 'Document flow parent',
            'rules' => 'required',
            'errors' => ['required' => 'Document flow parent is required']
        ],
        'data_document' => [
            'label' => 'Document flow document',
            'rules' => 'required',
            'errors' => ['required' => 'Document flow document is required']
        ],
    ];
}
