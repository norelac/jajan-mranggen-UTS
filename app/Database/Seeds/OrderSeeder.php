<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Order 1 - pembeli dewi (id: 4)
        $this->db->table('orders')->insert([
            'user_id'          => 4,
            'invoice_number'   => 'INV-2026050400001',
            'total_price'      => 100000,
            'shipping_cost'    => 15000,
            'total_weight'     => 700,
            'shipping_address' => 'Jl. Tembalang No. 7, Semarang, Jawa Tengah 50275',
            'payment_method'   => 'transfer_bank',
            'payment_status'   => 'paid',
            'status'           => 'delivered',
            'notes'            => 'Tolong dikemas dengan rapi ya kak',
            'created_at'       => date('Y-m-d H:i:s', strtotime('-5 days')),
            'updated_at'       => date('Y-m-d H:i:s', strtotime('-2 days')),
        ]);
        $orderId1 = $this->db->insertID();

        $this->db->table('order_items')->insertBatch([
            [
                'order_id'   => $orderId1,
                'product_id' => 1, // Kue Lapis Legit
                'quantity'   => 1,
                'price'      => 85000,
                'subtotal'   => 85000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'order_id'   => $orderId1,
                'product_id' => 2, // Klepon Mranggen
                'quantity'   => 1,
                'price'      => 15000,
                'subtotal'   => 15000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
        ]);

        // Order 2 - pembeli andi (id: 5)
        $this->db->table('orders')->insert([
            'user_id'          => 5,
            'invoice_number'   => 'INV-2026050400002',
            'total_price'      => 68000,
            'shipping_cost'    => 12000,
            'total_weight'     => 300,
            'shipping_address' => 'Jl. Pedurungan No. 22, Semarang, Jawa Tengah 50118',
            'payment_method'   => 'cod',
            'payment_status'   => 'pending',
            'status'           => 'processing',
            'notes'            => '',
            'created_at'       => date('Y-m-d H:i:s', strtotime('-2 days')),
            'updated_at'       => date('Y-m-d H:i:s', strtotime('-1 days')),
        ]);
        $orderId2 = $this->db->insertID();

        $this->db->table('order_items')->insertBatch([
            [
                'order_id'   => $orderId2,
                'product_id' => 4, // Keripik Tempe Pedas
                'quantity'   => 2,
                'price'      => 25000,
                'subtotal'   => 50000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'order_id'   => $orderId2,
                'product_id' => 5, // Rempeyek Kacang
                'quantity'   => 1,
                'price'      => 18000,
                'subtotal'   => 18000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
        ]);

        // Order 3 - pembeli dewi (id: 4) - pending
        $this->db->table('orders')->insert([
            'user_id'          => 4,
            'invoice_number'   => 'INV-2026050400003',
            'total_price'      => 150000,
            'shipping_cost'    => 20000,
            'total_weight'     => 1500,
            'shipping_address' => 'Jl. Tembalang No. 7, Semarang, Jawa Tengah 50275',
            'payment_method'   => 'dompet_digital',
            'payment_status'   => 'paid',
            'status'           => 'shipped',
            'notes'            => 'Hadiah ulang tahun, mohon dibungkus cantik',
            'created_at'       => date('Y-m-d H:i:s', strtotime('-1 days')),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);
        $orderId3 = $this->db->insertID();

        $this->db->table('order_items')->insert([
            'order_id'   => $orderId3,
            'product_id' => 9, // Hampers Jajan Mranggen
            'quantity'   => 1,
            'price'      => 150000,
            'subtotal'   => 150000,
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
        ]);
    }
}
