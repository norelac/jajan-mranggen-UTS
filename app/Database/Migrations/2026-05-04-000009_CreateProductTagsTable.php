<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductTagsTable extends Migration
{
    public function up()
    {
        // No-op: 'product_tags' table already created by 2026-05-04-000007_CreateTagsTable (batch 2).
        if (! $this->db->tableExists('product_tags')) {
            $this->forge->addField([
                'product_id' => ['type' => 'INT', 'unsigned' => true],
                'tag_id'     => ['type' => 'INT', 'unsigned' => true],
            ]);
            $this->forge->addPrimaryKey(['product_id', 'tag_id']);
            $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('tag_id', 'tags', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('product_tags');
        }
    }

    public function down()
    {
        // No-op: managed by the earlier migration
    }
}
