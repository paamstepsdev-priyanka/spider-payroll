<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsDirectEmployeeToEmployeesTable extends Migration
{
    public function up()
    {
        $fields = [
            'is_direct_employee' => [
                'type'       => 'ENUM',
                'constraint' => ['0', '1'],
                'default'    => '0',
                'after'      => 'employee_id',
            ],
        ];

        $this->forge->addColumn('employees', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', 'is_direct_employee');
    }
}
