<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Fake reviews for products - user IDs 4,5 are pembeli (dewi, andi)
        $reviews = [
            ['product_id' => 1, 'user_id' => 4, 'rating' => 5, 'comment' => 'Enak banget! Pedasnya pas, cocok buat camilan sore. Pasti beli lagi!', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 2, 'user_id' => 4, 'rating' => 4, 'comment' => 'Manisnya pas, lembut di lidah. Packagingnya juga rapi dan higienis.', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 3, 'user_id' => 5, 'rating' => 5, 'comment' => 'Renyah banget sampai ke rumah, gurihnya nendang! Recommended.', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 4, 'user_id' => 5, 'rating' => 4, 'comment' => 'Legitnya kayak buatan nenek sendiri. Produk autentik sekali.', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 5, 'user_id' => 4, 'rating' => 5, 'comment' => 'Terbaik! Respon penjual cepat, produk sesuai ekspektasi.', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => 1, 'user_id' => 5, 'rating' => 4, 'comment' => 'Pedasnya mantap, tapi waktu pengiriman agak lama. Overall bagus!', 'created_at' => $now, 'updated_at' => $now],
        ];

        $this->db->table('reviews')->insertBatch($reviews);
    }
}
