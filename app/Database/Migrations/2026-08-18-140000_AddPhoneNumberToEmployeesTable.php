<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneNumberToEmployeesTable extends Migration
{
    public function up()
    {
        $fields = [
            'phone_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'default'    => null,
                'after'      => 'biometric_code',
            ],
        ];

        $this->forge->addColumn('employees', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('employees', 'phone_number');
    }
}
