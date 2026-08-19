<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $email    = 'priyanka@gmail.com';
        $username = 'priyanka@gmail.com';
        $password = '12345678';
        $role     = 'super_admin';
        $status   = 1; // Active status

        $hasEmailField = $this->db->fieldExists('email', 'users');

        $query = $this->db->table('users')->where('username', $username);
        if ($hasEmailField) {
            $query->orWhere('email', $email);
        }

        $existingUser = $query->get()->getRowArray();

        $userData = [
            'name'       => 'Priyanka',
            'username'   => $username,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role'       => $role,
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ];

        if ($hasEmailField) {
            $userData['email'] = $email;
        }

        if ($existingUser) {
            $this->db->table('users')
                ->where('id', $existingUser['id'])
                ->update($userData);
        } else {
            $userData['created_at'] = date('Y-m-d H:i:s');
            $this->db->table('users')->insert($userData);
        }
    }
}
