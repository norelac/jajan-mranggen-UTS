<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Clear existing data safely (disable FK checks first)
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('product_tags')->truncate();
        $this->db->table('tags')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        $tags = [
            ['name' => 'Pedas', 'slug' => 'pedas', 'color' => '#E74C3C', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manis', 'slug' => 'manis', 'color' => '#F39C12', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gurih', 'slug' => 'gurih', 'color' => '#27AE60', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Halal', 'slug' => 'halal', 'color' => '#2ECC71', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tradisional', 'slug' => 'tradisional', 'color' => '#8E44AD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kering', 'slug' => 'kering', 'color' => '#E67E22', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Basah', 'slug' => 'basah', 'color' => '#3498DB', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Goreng', 'slug' => 'goreng', 'color' => '#FF6B35', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kukus', 'slug' => 'kukus', 'color' => '#1ABC9C', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Premium', 'slug' => 'premium', 'color' => '#F1C40F', 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('tags')->insertBatch($tags);

        // Attach tags to products (example - product IDs 1-6)
        $productTags = [
            ['product_id' => 1, 'tag_id' => 1], // pedas
            ['product_id' => 1, 'tag_id' => 4], // halal
            ['product_id' => 1, 'tag_id' => 5], // tradisional
            ['product_id' => 2, 'tag_id' => 2], // manis
            ['product_id' => 2, 'tag_id' => 4], // halal
            ['product_id' => 2, 'tag_id' => 9], // kukus
            ['product_id' => 3, 'tag_id' => 3], // gurih
            ['product_id' => 3, 'tag_id' => 8], // goreng
            ['product_id' => 3, 'tag_id' => 4], // halal
            ['product_id' => 4, 'tag_id' => 2], // manis
            ['product_id' => 4, 'tag_id' => 5], // tradisional
            ['product_id' => 5, 'tag_id' => 3], // gurih
            ['product_id' => 5, 'tag_id' => 6], // kering
            ['product_id' => 6, 'tag_id' => 1], // pedas
            ['product_id' => 6, 'tag_id' => 8], // goreng
        ];

        $this->db->table('product_tags')->insertBatch($productTags);
    }
}
