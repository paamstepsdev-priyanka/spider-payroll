<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeContractorIdNullableInEmployeesTable extends Migration
{
    public function up()
    {
        $fields = [
            'contractor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('employees', $fields);
    }

    public function down()
    {
        $fields = [
            'contractor_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('employees', $fields);
    }
}
