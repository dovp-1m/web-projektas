<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker          = Factory::create();
        $hashedPassword = password_hash('Password123!', PASSWORD_BCRYPT);
        $batch          = [];

        for ($i = 0; $i < 100; $i++) {
            $batch[] = [
                'username'   => $faker->unique()->userName(),
                'email'      => $faker->unique()->safeEmail(),
                'password'   => $hashedPassword,
                'first_name' => $faker->firstName(),
                'last_name'  => $faker->lastName(),
                'role'       => 'user',
                'is_active'  => 1,
                'created_at' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        // Insert in batches of 50 to avoid memory issues
        foreach (array_chunk($batch, 50) as $chunk) {
            $this->db->table('users')->insertBatch($chunk);
        }

        echo "Inserted 100 users.\n";
    }
}