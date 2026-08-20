<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAccountHolderNameToContractorsAndEmployeesTable extends Migration
{
    public function up()
    {
        $fields = [
            'account_holder_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'bank_name',
            ],
        ];

        // Add to contractors table
        if ($this->db->tableExists('contractors') && !$this->db->fieldExists('account_holder_name', 'contractors')) {
            $this->forge->addColumn('contractors', $fields);
        }

        // Add to employees table
        if ($this->db->tableExists('employees') && !$this->db->fieldExists('account_holder_name', 'employees')) {
            $this->forge->addColumn('employees', $fields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('contractors') && $this->db->fieldExists('account_holder_name', 'contractors')) {
            $this->forge->dropColumn('contractors', 'account_holder_name');
        }

        if ($this->db->tableExists('employees') && $this->db->fieldExists('account_holder_name', 'employees')) {
            $this->forge->dropColumn('employees', 'account_holder_name');
        }
    }
}
