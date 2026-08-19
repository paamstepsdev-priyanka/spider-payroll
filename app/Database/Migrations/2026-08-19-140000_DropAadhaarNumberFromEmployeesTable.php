<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropAadhaarNumberFromEmployeesTable extends Migration
{
    public function up()
    {
        if ($this->db->fieldExists('aadhaar_number', 'employees')) {
            $this->forge->dropColumn('employees', 'aadhaar_number');
        }
    }

    public function down()
    {
        if (!$this->db->fieldExists('aadhaar_number', 'employees')) {
            $this->forge->addColumn('employees', [
                'aadhaar_number' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                    'null'       => true,
                    'default'    => null,
                ],
            ]);
        }
    }
}
