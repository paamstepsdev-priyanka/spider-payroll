<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMonthlyAttendanceAndCalculatedSalaryTables extends Migration
{
    public function up()
    {
        // 1. Table: monthly_attendance
        if (!$this->db->tableExists('monthly_attendance')) {
            $this->forge->addField([
                'attendance_id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => false,
                    'auto_increment' => true,
                ],
                'employee_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'month_date' => [
                    'type' => 'DATE',
                    'null' => false,
                ],
                'total_month_days' => [
                    'type'       => 'FLOAT',
                    'default'    => 31,
                ],
                'attended_days' => [
                    'type'       => 'FLOAT',
                    'default'    => 0,
                ],
                'leave_days' => [
                    'type'       => 'FLOAT',
                    'default'    => 0,
                ],
                'leave_not_deducted' => [
                    'type'       => 'FLOAT',
                    'default'    => 0,
                ],
                'net_days_payable' => [
                    'type'       => 'FLOAT',
                    'default'    => 0,
                ],
                'remarks' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('attendance_id', true);
            $this->forge->addUniqueKey(['employee_id', 'month_date'], 'uk_employee_month');
            $this->forge->createTable('monthly_attendance', true);
        }

        // 2. Table: calculated_salary
        if (!$this->db->tableExists('calculated_salary')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => false,
                    'auto_increment' => true,
                ],
                'employee_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'contractor_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'month_date' => [
                    'type' => 'DATE',
                    'null' => false,
                ],
                'monthly_base_salary' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'default'    => 0.00,
                ],
                'net_days_payable' => [
                    'type'       => 'FLOAT',
                    'default'    => 0,
                ],
                'calculated_salary' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                    'default'    => 0.00,
                ],
                'remarks' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey(['employee_id', 'month_date'], 'uk_calc_salary_employee_month');
            $this->forge->createTable('calculated_salary', true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('calculated_salary', true);
        $this->forge->dropTable('monthly_attendance', true);
    }
}
