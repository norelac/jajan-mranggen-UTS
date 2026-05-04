<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCoordinatesToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'latitude'  => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true, 'after' => 'address'],
            'longitude' => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true, 'after' => 'latitude'],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['latitude', 'longitude']);
    }
}
