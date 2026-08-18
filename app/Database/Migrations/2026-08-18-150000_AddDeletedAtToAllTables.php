<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToAllTables extends Migration
{
    public function up()
    {
        $fields = [
            'deleted_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ];

        if ($this->db->tableExists('users') && !$this->db->fieldExists('deleted_at', 'users')) {
            $this->forge->addColumn('users', $fields);
        }

        if ($this->db->tableExists('contractors') && !$this->db->fieldExists('deleted_at', 'contractors')) {
            $this->forge->addColumn('contractors', $fields);
        }

        if ($this->db->tableExists('employees') && !$this->db->fieldExists('deleted_at', 'employees')) {
            $this->forge->addColumn('employees', $fields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users') && $this->db->fieldExists('deleted_at', 'users')) {
            $this->forge->dropColumn('users', 'deleted_at');
        }

        if ($this->db->tableExists('contractors') && $this->db->fieldExists('deleted_at', 'contractors')) {
            $this->forge->dropColumn('contractors', 'deleted_at');
        }

        if ($this->db->tableExists('employees') && $this->db->fieldExists('deleted_at', 'employees')) {
            $this->forge->dropColumn('employees', 'deleted_at');
        }
    }
}
