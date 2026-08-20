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
        'is_direct_employee',
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
        'account_holder_name',
        'bank_account_number',
        'ifsc_code',
        'bank_branch',
        'pan_number',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'employee_name'       => 'required|max_length[150]',
        'monthly_base_salary' => 'required|numeric',
        'date_of_joining'     => 'required|valid_date',
        'status'              => 'required|in_list[active,inactive,relieved]',
        'bank_name'           => 'required|max_length[100]',
        'account_holder_name' => 'required|max_length[150]',
        'bank_branch'         => 'required|max_length[100]',
        'bank_account_number' => 'required|min_length[9]|max_length[50]|is_unique[employees.bank_account_number,employee_id,{employee_id}]',
        'ifsc_code'           => 'required|max_length[20]|regex_match[/^[A-Z]{4}0[A-Z0-9]{6}$/]|is_unique[employees.ifsc_code,employee_id,{employee_id}]',
    ];

    protected $validationMessages = [
        'bank_name' => [
            'required'   => 'Bank Name is required.',
            'max_length' => 'Bank Name cannot exceed 100 characters.',
        ],
        'bank_branch' => [
            'required'   => 'Bank Branch is required.',
            'max_length' => 'Bank Branch cannot exceed 100 characters.',
        ],
        'bank_account_number' => [
            'required'   => 'Bank Account Number is required.',
            'min_length' => 'Bank Account Number must be at least 9 characters.',
            'max_length' => 'Bank Account Number cannot exceed 50 characters.',
            'is_unique'  => 'This Bank Account Number is already registered by another employee.',
        ],
        'ifsc_code' => [
            'required'    => 'IFSC Code is required.',
            'regex_match' => 'Please enter a valid 11-character IFSC Code (e.g. SBIN0000005).',
            'is_unique'   => 'This IFSC Code is already registered by another employee.',
        ],
    ];

    /**
     * Filter employees by search term, contractor, status, and sorting with pagination
     *
     * @param string|null $search
     * @param int|null $contractorId
     * @param string|null $status
     * @param string $sortColumn
     * @param string $sortOrder
     * @param int $perPage
     * @return array
     */
    public function getFilteredEmployees(?string $search = null, ?int $contractorId = null, ?string $status = null, string $sortColumn = 'employee_id', string $sortOrder = 'DESC', int $perPage = 10): array
    {
        $this->select('employees.*, contractors.contractor_name');
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

        $validSortColumns = [
            'employee_id'         => 'employees.employee_id',
            'employee_name'       => 'employees.employee_name',
            'biometric_code'      => 'employees.biometric_code',
            'contractor_name'     => 'contractors.contractor_name',
            'monthly_base_salary' => 'employees.monthly_base_salary',
            'date_of_joining'     => 'employees.date_of_joining',
            'status'              => 'employees.status',
        ];

        $col = $validSortColumns[$sortColumn] ?? 'employees.employee_id';
        $dir = (strtoupper($sortOrder) === 'ASC') ? 'ASC' : 'DESC';

        $this->orderBy($col, $dir);

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
        return $this->select('employees.*, contractors.contractor_name')
            ->join('contractors', 'contractors.contractor_id = employees.contractor_id', 'left')
            ->where('employees.employee_id', $id)
            ->first();
    }
}
