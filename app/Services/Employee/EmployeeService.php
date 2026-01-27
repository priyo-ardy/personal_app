<?php

namespace App\Services\Employee;

use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\DataTableRepository;
use App\Validation\Employee\EmployeeValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\ResponseTrait;
