<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name'        => 'Kue Tradisional',
                'slug'        => 'kue-tradisional',
                'description' => 'Aneka kue tradisional khas nusantara yang lezat dan autentik',
                'image'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Snack Gurih',
                'slug'        => 'snack-gurih',
                'description' => 'Berbagai macam camilan gurih yang renyah dan menggugah selera',
                'image'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Minuman Tradisional',
                'slug'        => 'minuman-tradisional',
                'description' => 'Minuman khas nusantara yang menyegarkan dan berkhasiat',
                'image'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Makanan Berat',
                'slug'        => 'makanan-berat',
                'description' => 'Hidangan utama khas daerah yang mengenyangkan',
                'image'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Oleh-oleh',
                'slug'        => 'oleh-oleh',
                'description' => 'Paket oleh-oleh khas Mranggen dan sekitarnya',
                'image'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Kue Kering',
                'slug'        => 'kue-kering',
                'description' => 'Kue kering aneka rasa untuk berbagai acara spesial',
                'image'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($categories);
    }
}
