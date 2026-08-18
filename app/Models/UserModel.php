<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'username',
        'password',
        'role',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[2]|max_length[100]',
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'role'     => 'required|in_list[super_admin]',
        'status'   => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Name is required.',
            'min_length' => 'Name must be at least 2 characters long.',
            'max_length' => 'Name cannot exceed 100 characters.',
        ],
        'username' => [
            'required'  => 'Username is required.',
            'is_unique' => 'This username is already taken. Please choose another.',
        ],
        'role' => [
            'required' => 'Role is required.',
            'in_list'  => 'Please select a valid role (Super Admin).',
        ],
        'status' => [
            'required' => 'Status is required.',
            'in_list'  => 'Please select a valid status (Active or Inactive).',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Filter users by search term, role, and status with pagination
     *
     * @param string|null $search
     * @param string|null $role
     * @param string|null $status
     * @param int $perPage
     * @return array
     */
    public function getFilteredUsers(?string $search = null, ?string $role = null, ?string $status = null, int $perPage = 10): array
    {
        if (!empty($search)) {
            $this->groupStart()
                ->like('name', $search)
                ->orLike('username', $search)
                ->groupEnd();
        }

        if (!empty($role)) {
            $this->where('role', $role);
        }

        if ($status !== null && $status !== '') {
            $this->where('status', (int) $status);
        }

        $this->orderBy('id', 'DESC');

        return $this->paginate($perPage);
    }
}
