<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTagsTable extends Migration
{
    public function up()
    {
        // No-op: 'tags' table already created by 2026-05-04-000007_CreateTagsTable (batch 2).
        // Using IF NOT EXISTS to safely skip if table exists.
        if (! $this->db->tableExists('tags')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'name'       => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
                'slug'       => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
                'description'=> ['type' => 'TEXT', 'null' => true],
                'color'      => ['type' => 'VARCHAR', 'constraint' => 7, 'default' => '#FF6B35'],
                'icon'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('id');
            $this->forge->createTable('tags');
        }
    }

    public function down()
    {
        // No-op: managed by the earlier migration
    }
}
