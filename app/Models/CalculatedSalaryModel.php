<?php

namespace App\Models;

use CodeIgniter\Model;

class CalculatedSalaryModel extends Model
{
    protected $table            = 'calculated_salary';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'employee_id',
        'contractor_id',
        'month_date',
        'monthly_base_salary',
        'net_days_payable',
        'calculated_salary',
        'remarks',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get calculated salary indexed by employee_id for a given month_date
     */
    public function getCalculatedSalaryByMonth(string $monthDate): array
    {
        $records = $this->where('month_date', $monthDate)->findAll();
        $indexed = [];
        foreach ($records as $row) {
            $indexed[$row['employee_id']] = $row;
        }
        return $indexed;
    }
}
