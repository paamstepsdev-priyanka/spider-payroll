<?php

namespace App\Models;

use CodeIgniter\Model;

class ContractorModel extends Model
{
    protected $table            = 'contractors';
    protected $primaryKey       = 'contractor_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'contractor_code',
        'contractor_name',
        'phone_number',
        'email',
        'address',
        'bank_name',
        'branch_name',
        'bank_account_number',
        'ifsc_code',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'contractor_name'     => 'required|max_length[150]',
        'contractor_code'     => 'required|max_length[30]|is_unique[contractors.contractor_code,contractor_id,{contractor_id}]',
        'phone_number'        => 'permit_empty|max_length[20]|is_unique[contractors.phone_number,contractor_id,{contractor_id}]',
        'email'               => 'permit_empty|valid_email|max_length[100]',
        'bank_name'           => 'permit_empty|max_length[100]',
        'branch_name'         => 'permit_empty|max_length[100]',
        'bank_account_number' => 'required|max_length[50]|is_unique[contractors.bank_account_number,contractor_id,{contractor_id}]',
        'ifsc_code'           => 'required|max_length[20]|is_unique[contractors.ifsc_code,contractor_id,{contractor_id}]',
        'status'              => 'required|in_list[active,inactive]',
    ];

    protected $validationMessages = [
        'contractor_name' => [
            'required'   => 'Contractor Name is required.',
            'max_length' => 'Contractor Name cannot exceed 150 characters.',
        ],
        'contractor_code' => [
            'required'   => 'Contractor Code is required.',
            'max_length' => 'Contractor Code cannot exceed 30 characters.',
            'is_unique'  => 'This Contractor Code is already registered. Please enter a unique code.',
        ],
        'phone_number' => [
            'max_length' => 'Phone Number cannot exceed 20 characters.',
            'is_unique'  => 'This Phone Number is already registered by another contractor.',
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address.',
            'max_length'  => 'Email cannot exceed 100 characters.',
        ],
        'bank_name' => [
            'max_length' => 'Bank Name cannot exceed 100 characters.',
        ],
        'branch_name' => [
            'max_length' => 'Branch Name cannot exceed 100 characters.',
        ],
        'bank_account_number' => [
            'required'   => 'Bank Account Number is required.',
            'max_length' => 'Bank Account Number cannot exceed 50 characters.',
            'is_unique'  => 'This Bank Account Number is already registered by another contractor.',
        ],
        'ifsc_code' => [
            'required'   => 'IFSC Code is required.',
            'max_length' => 'IFSC Code cannot exceed 20 characters.',
            'is_unique'  => 'This IFSC Code is already registered by another contractor.',
        ],
        'status' => [
            'required' => 'Status selection is required.',
            'in_list'  => 'Please select a valid status (Active or Inactive).',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Filter contractors by search term and status with pagination
     *
     * @param string|null $search
     * @param string|null $status
     * @param int $perPage
     * @return array
     */
    public function getFilteredContractors(?string $search = null, ?string $status = null, int $perPage = 10): array
    {
        if (!empty($search)) {
            $this->groupStart()
                ->like('contractor_name', $search)
                ->orLike('contractor_code', $search)
                ->orLike('phone_number', $search)
                ->orLike('email', $search)
                ->orLike('bank_name', $search)
                ->orLike('branch_name', $search)
                ->groupEnd();
        }

        if (!empty($status)) {
            $this->where('status', $status);
        }

        $this->orderBy('contractor_id', 'DESC');

        return $this->paginate($perPage);
    }
}
