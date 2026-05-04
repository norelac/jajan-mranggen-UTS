<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationToProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
                'comment'    => 'Latitude from OpenStreetMap',
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
                'comment'    => 'Longitude from OpenStreetMap',
            ],
            'address' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Full address from Nominatim API',
            ],
            'geohash' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
                'null'       => true,
                'index'      => true,
                'comment'    => 'Geohash for spatial queries',
            ],
            'average_rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '2,1',
                'default'    => 0,
                'comment'    => 'Average rating 0-5',
            ],
            'total_ratings' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'comment'    => 'Total number of ratings',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['latitude', 'longitude', 'address', 'geohash', 'average_rating', 'total_ratings']);
    }
}
