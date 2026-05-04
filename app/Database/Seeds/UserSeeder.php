<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username'   => 'admin',
                'email'      => 'admin@jajanmranggen.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name'  => 'Administrator',
                'phone'      => '081234567890',
                'address'    => 'Jl. Mranggen No. 1, Semarang',
                'role'       => 'admin',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'bu_sari',
                'email'      => 'sari@jajanmranggen.com',
                'password'   => password_hash('penjual123', PASSWORD_DEFAULT),
                'full_name'  => 'Sari Lestari',
                'phone'      => '082345678901',
                'address'    => 'Jl. Pasar Mranggen No. 12, Demak',
                'role'       => 'penjual',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'pak_budi',
                'email'      => 'budi@jajanmranggen.com',
                'password'   => password_hash('penjual123', PASSWORD_DEFAULT),
                'full_name'  => 'Budi Santoso',
                'phone'      => '083456789012',
                'address'    => 'Jl. Raya Mranggen No. 45, Demak',
                'role'       => 'penjual',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'dewi_cantik',
                'email'      => 'dewi@gmail.com',
                'password'   => password_hash('pembeli123', PASSWORD_DEFAULT),
                'full_name'  => 'Dewi Rahayu',
                'phone'      => '085678901234',
                'address'    => 'Jl. Tembalang No. 7, Semarang',
                'role'       => 'pembeli',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'andi_ganteng',
                'email'      => 'andi@gmail.com',
                'password'   => password_hash('pembeli123', PASSWORD_DEFAULT),
                'full_name'  => 'Andi Firmansyah',
                'phone'      => '086789012345',
                'address'    => 'Jl. Pedurungan No. 22, Semarang',
                'role'       => 'pembeli',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mbak_rina',
                'email'      => 'rina@jajanmranggen.com',
                'password'   => password_hash('penjual123', PASSWORD_DEFAULT),
                'full_name'  => 'Rina Kurniawati',
                'phone'      => '087890123456',
                'address'    => 'Jl. Mranggen Tengah No. 8, Demak',
                'role'       => 'penjual',
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
