<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 120],
            'name_id'     => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 140],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_active'   => ['type' => 'INTEGER', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('categories', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('categories', true);
    }
}

