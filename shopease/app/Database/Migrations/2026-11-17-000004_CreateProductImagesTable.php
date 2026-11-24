<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductImagesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'         => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INTEGER', 'constraint' => 11, 'unsigned' => true],
            'filename'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_primary' => ['type' => 'INTEGER', 'constraint' => 1, 'default' => 0],
            'sort_order' => ['type' => 'INTEGER', 'constraint' => 11, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_images', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('product_images', true);
    }
}

