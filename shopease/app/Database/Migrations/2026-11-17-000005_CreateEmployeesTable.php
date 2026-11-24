<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'            => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'employee_code' => ['type' => 'VARCHAR', 'constraint' => 32],
            'full_name'     => ['type' => 'VARCHAR', 'constraint' => 120],
            'work_email'    => ['type' => 'VARCHAR', 'constraint' => 120],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'position'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'department'    => ['type' => 'VARCHAR', 'constraint' => 80],
            'joined_at'     => ['type' => 'DATE'],
            'status'        => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('employee_code');
        $this->forge->addUniqueKey('work_email');
        $this->forge->addKey('department');
        $this->forge->addKey('status');
        $this->forge->createTable('employees', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('employees', true);
    }
}

