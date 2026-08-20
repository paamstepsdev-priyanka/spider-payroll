<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDobToContractorsTable extends Migration
{
    public function up()
    {
        $fields = [
            'dob' => [
                'type'       => 'DATE',
                'null'       => true,
                'default'    => null,
                'after'      => 'phone_number',
            ],
        ];

        $this->forge->addColumn('contractors', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('contractors', 'dob');
    }
}
