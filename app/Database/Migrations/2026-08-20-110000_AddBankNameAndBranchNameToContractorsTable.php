<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBankNameAndBranchNameToContractorsTable extends Migration
{
    public function up()
    {
        $fieldsToAdd = [];

        if (!$this->db->fieldExists('bank_name', 'contractors')) {
            $fieldsToAdd['bank_name'] = [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'default'    => null,
                'after'      => 'address',
            ];
        }

        if (!$this->db->fieldExists('branch_name', 'contractors')) {
            $fieldsToAdd['branch_name'] = [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'default'    => null,
                'after'      => isset($fieldsToAdd['bank_name']) ? 'bank_name' : 'address',
            ];
        }

        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('contractors', $fieldsToAdd);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('bank_name', 'contractors')) {
            $this->forge->dropColumn('contractors', 'bank_name');
        }
        if ($this->db->fieldExists('branch_name', 'contractors')) {
            $this->forge->dropColumn('contractors', 'branch_name');
        }
    }
}
