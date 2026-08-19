<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class UserController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display list of users with search and filters (supports standard & AJAX requests)
     */
    public function index()
    {
        $search  = $this->request->getGet('search');
        $role    = $this->request->getGet('role');
        $status  = $this->request->getGet('status');
        $perPage = 10;

        $users = $this->userModel->getFilteredUsers($search, $role, $status, $perPage);

        $data = [
            'title'  => 'User List - Spider Payroll',
            'users'  => $users,
            'pager'  => $this->userModel->pager,
            'search' => $search,
            'role'   => $role,
            'status' => $status,
        ];

        if ($this->request->isAJAX()) {
            return view('users/_table', $data);
        }

        return view('users/index', $data);
    }

    /**
     * Show form to create a new user
     */
    public function create()
    {
        $data = [
            'title' => 'Add User - Spider Payroll',
        ];

        return view('users/create', $data);
    }

    /**
     * Store newly created user in database
     */
    public function store()
    {
        $rules = [
            'name'             => 'required|min_length[2]|max_length[100]',
            'username'         => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role'             => 'required|in_list[super_admin]',
            'status'           => 'required|in_list[0,1]',
        ];

        $messages = [
            'name' => [
                'required' => 'Full Name is required.',
            ],
            'username' => [
                'required'  => 'Username is required.',
                'is_unique' => 'This username is already taken. Please choose another.',
            ],
            'password' => [
                'required'   => 'Password is required.',
                'min_length' => 'Password must be at least 6 characters long.',
            ],
            'confirm_password' => [
                'required' => 'Please confirm your password.',
                'matches'  => 'Confirm Password does not match Password.',
            ],
            'role' => [
                'required' => 'Role selection is required.',
                'in_list'  => 'Selected role is invalid.',
            ],
            'status' => [
                'required' => 'Status selection is required.',
                'in_list'  => 'Selected status is invalid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted validation errors.',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'name'     => trim($this->request->getPost('name')),
            'username' => trim($this->request->getPost('username')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'status'   => (int) $this->request->getPost('status'),
        ];

        if ($this->userModel->insert($userData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'   => 'success',
                    'message'  => 'User account created successfully.',
                    'redirect' => site_url('users'),
                ]);
            }
            return redirect()->to('users')->with('success', 'User account created successfully.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to create user account. Please try again.',
            ])->setStatusCode(400);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create user account. Please try again.');
    }

    /**
     * View user details
     */
    public function view($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound("User with ID {$id} not found.");
        }

        $data = [
            'title' => 'View User #' . $user['id'] . ' - Spider Payroll',
            'user'  => $user,
        ];

        return view('users/view', $data);
    }

    /**
     * Show form to edit user
     */
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound("User with ID {$id} not found.");
        }

        $data = [
            'title' => 'Edit User #' . $user['id'] . ' - Spider Payroll',
            'user'  => $user,
        ];

        return view('users/edit', $data);
    }

    /**
     * Update user details in database
     */
    public function update($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound("User with ID {$id} not found.");
        }

        $rules = [
            'name'     => 'required|min_length[2]|max_length[100]',
            'username' => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'role'     => 'required|in_list[super_admin]',
            'status'   => 'required|in_list[0,1]',
        ];

        $messages = [
            'name' => [
                'required' => 'Full Name is required.',
            ],
            'username' => [
                'required'  => 'Username is required.',
                'is_unique' => 'This username is already taken by another user.',
            ],
            'role' => [
                'required' => 'Role selection is required.',
            ],
            'status' => [
                'required' => 'Status selection is required.',
            ],
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password']         = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';

            $messages['password']['min_length']       = 'New password must be at least 6 characters long.';
            $messages['confirm_password']['matches'] = 'Confirm Password does not match New Password.';
        }

        if (!$this->validate($rules, $messages)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please correct the highlighted validation errors.',
                    'errors'  => $this->validator->getErrors(),
                ])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'name'     => trim($this->request->getPost('name')),
            'username' => trim($this->request->getPost('username')),
            'role'     => $this->request->getPost('role'),
            'status'   => (int) $this->request->getPost('status'),
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->skipValidation(true)->update($id, $updateData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'   => 'success',
                    'message'  => 'User account updated successfully.',
                    'redirect' => site_url('users'),
                ]);
            }
            return redirect()->to('users')->with('success', 'User account updated successfully.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to update user account. Please try again.',
            ])->setStatusCode(400);
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update user account. Please try again.');
    }

    /**
     * Toggle status of a user (Active <-> Inactive) via POST
     */
    public function toggleStatus($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('users')->with('error', 'User not found.');
        }

        $newStatus  = ((int) $user['status'] === 1) ? 0 : 1;
        $statusText = $newStatus === 1 ? 'activated' : 'deactivated';

        if ($this->userModel->skipValidation(true)->update($id, ['status' => $newStatus])) {
            return redirect()->to('users')->with('success', "User account {$statusText} successfully.");
        }

        return redirect()->to('users')->with('error', 'Failed to update user status.');
    }

    /**
     * Delete user via POST
     */
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('users')->with('error', 'User not found.');
        }

        $currentLoggedInUserId = session()->get('user_id');
        if ($currentLoggedInUserId && (int)$currentLoggedInUserId === (int)$id) {
            return redirect()->to('users')->with('error', 'Action denied: You cannot delete your currently logged-in account.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('users')->with('success', "User '{$user['username']}' deleted successfully.");
        }

        return redirect()->to('users')->with('error', 'Failed to delete user.');
    }
}
