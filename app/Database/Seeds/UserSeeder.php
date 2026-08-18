<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Super Admin',
                'username'   => 'super_admin',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'super_admin',
                'status'     => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Demo User',
                'username'   => 'demouser',
                'password'   => password_hash('password123', PASSWORD_DEFAULT),
                'role'       => 'super_admin',
                'status'     => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $user) {
            $exists = $this->db->table('users')->where('username', $user['username'])->countAllResults();
            if ($exists === 0) {
                $this->db->table('users')->insert($user);
            }
        }
    }
}
