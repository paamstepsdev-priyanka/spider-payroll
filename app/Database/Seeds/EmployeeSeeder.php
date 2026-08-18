<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'contractor_id'       => 1,
            'biometric_code'      => 'BIO001',
            'employee_name'       => 'Rahul Patil',
            'gender'              => 'male',
            'date_of_birth'       => '1995-05-15',
            'date_of_joining'     => '2026-06-01',
            'date_of_leaving'     => null,
            'exit_reason'         => null,
            'designation'         => 'Helper',
            'department'          => 'Production',
            'monthly_base_salary' => 18000.00,
            'bank_name'           => 'HDFC Bank',
            'bank_account_number' => '001234567890',
            'ifsc_code'           => 'HDFC0001234',
            'pan_number'          => null,
            'aadhaar_number'      => null,
            'status'              => 'active',
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        // Using Query Builder
        $this->db->table('employees')->insert($data);
    }
}
