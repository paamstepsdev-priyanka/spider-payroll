<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display Login Page
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/users');
        }

        return view('auth/login', [
            'title' => 'Login - Spider Payroll'
        ]);
    }

    /**
     * Authenticate User Login
     */
    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Please fill in both Email and Password fields.'
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Please fill in both Email and Password fields.');
        }

        $user = $this->userModel->where('username', $username)->first();

        if (!$user || (int)$user['status'] !== 1 || !password_verify($password, $user['password'])) {
            $errorMsg = 'Invalid email or password, or account is inactive.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => $errorMsg
                ]);
            }
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        // Set Session Data
        session()->set([
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'       => 'success',
                'message'      => 'Login successful! Redirecting...',
                'redirect'     => site_url('users'),
                'redirect_url' => site_url('users')
            ]);
        }

        return redirect()->to('users')->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    /**
     * Logout User and redirect to Login Page
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login')->with('success', 'Logged out successfully.');
    }
}
