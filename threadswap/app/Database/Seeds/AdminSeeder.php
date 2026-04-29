<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        $this->db->table('users')->insert([
            'username'   => 'admin',
            'email'      => 'admin@threadswap.lt',
            'password'   => password_hash('Admin1234!', PASSWORD_BCRYPT),
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'role'       => 'admin',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Default categories
        $categories = [
            ["Women's Tops",    'womens-tops'],
            ["Women's Bottoms", 'womens-bottoms'],
            ["Men's Tops",      'mens-tops'],
            ["Men's Bottoms",   'mens-bottoms'],
            ['Dresses & Skirts','dresses-skirts'],
            ['Jackets & Coats', 'jackets-coats'],
            ['Shoes',           'shoes'],
            ['Accessories',     'accessories'],
            ['Sportswear',      'sportswear'],
            ['Kids',            'kids'],
        ];

        foreach ($categories as [$name, $slug]) {
            // Skip if already exists
            if ($this->db->table('categories')->where('slug', $slug)->countAllResults() === 0) {
                $this->db->table('categories')->insert([
                    'name'       => $name,
                    'slug'       => $slug,
                    'is_active'  => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}