<?php

namespace App\Interfaces;

interface DepartmentInterface
{
    public function generateCode(string $prefix, string $column = 'code', int $padding = 4);
}
