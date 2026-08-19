<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBankBranchToEmployeesTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('bank_branch', 'employees')) {
            $fields = [
                'bank_branch' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'ifsc_code',
                ],
            ];
            $this->forge->addColumn('employees', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('bank_branch', 'employees')) {
            $this->forge->dropColumn('employees', 'bank_branch');
        }
    }
}
