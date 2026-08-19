<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMonthlyPayrollStatusTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'month_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'attendance_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'not_started',
            ],
            'attendance_frozen_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'salary_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
            ],
            'salary_frozen_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'disbursement_status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
            ],
            'disbursement_completed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('month_date');
        $this->forge->createTable('monthly_payroll_status');
    }

    public function down()
    {
        $this->forge->dropTable('monthly_payroll_status');
    }
}
