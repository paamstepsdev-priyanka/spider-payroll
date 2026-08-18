<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table            = 'employees';
    protected $primaryKey       = 'employee_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'contractor_id',
        'biometric_code',
        'employee_name',
        'gender',
        'date_of_birth',
        'date_of_joining',
        'date_of_leaving',
        'exit_reason',
        'designation',
        'department',
        'monthly_base_salary',
        'bank_name',
        'bank_account_number',
        'ifsc_code',
        'pan_number',
        'aadhaar_number',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Filter employees by search term, contractor, and status with pagination
     *
     * @param string|null $search
     * @param int|null $contractorId
     * @param string|null $status
     * @param int $perPage
     * @return array
     */
    public function getFilteredEmployees(?string $search = null, ?int $contractorId = null, ?string $status = null, int $perPage = 10): array
    {
        $this->select('employees.*, contractors.contractor_name, contractors.contractor_code');
        $this->join('contractors', 'contractors.contractor_id = employees.contractor_id', 'left');

        if (!empty($search)) {
            $this->groupStart()
                ->like('employees.employee_name', $search)
                ->orLike('employees.biometric_code', $search)
                ->orLike('employees.designation', $search)
                ->orLike('employees.department', $search)
                ->groupEnd();
        }

        if (!empty($contractorId)) {
            $this->where('employees.contractor_id', $contractorId);
        }

        if (!empty($status)) {
            $this->where('employees.status', $status);
        }

        $this->orderBy('employees.employee_id', 'DESC');

        return $this->paginate($perPage);
    }

    /**
     * Get single employee detail with contractor name
     *
     * @param int $id
     * @return array|null
     */
    public function getEmployeeWithContractor(int $id): ?array
    {
        return $this->select('employees.*, contractors.contractor_name, contractors.contractor_code')
            ->join('contractors', 'contractors.contractor_id = employees.contractor_id', 'left')
            ->where('employees.employee_id', $id)
            ->first();
    }
}
