<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'employee_code',
        'full_name',
        'work_email',
        'phone',
        'position',
        'department',
        'joined_at',
        'status',
    ];
    protected $useTimestamps    = true;

    public function distinctDepartments(): array
    {
        $rows = $this->select('department')
            ->distinct()
            ->orderBy('department', 'ASC')
            ->findAll();

        return array_column($rows, 'department');
    }
}

