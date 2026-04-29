<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ItemSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $userIds     = array_column(
            $this->db->table('users')->where('role', 'user')->get()->getResultArray(),
            'id'
        );
        $categoryIds = array_column(
            $this->db->table('categories')->get()->getResultArray(),
            'id'
        );

        if (empty($userIds) || empty($categoryIds)) {
            echo "ERROR: Run AdminSeeder and UserSeeder first!\n";
            return;
        }

        $adjectives = ['Vintage', 'Casual', 'Classic', 'Stylish', 'Slim Fit', 'Oversized', 'Cotton', 'Premium', 'Lightweight', 'Graphic'];

        $itemTypes = [
            'Nike' => ['Air Force 1', 'Jordan 1', 'Running Shoes', 'Sport Hoodie', 'Tech Fleece', 'Training Shorts'],
            'Adidas' => ['Superstar', 'Ultraboost', 'Tracksuit', 'Gazelle', 'Originals Tee'],
            'H&M' => ['Basic Tee', 'Denim Jacket', 'Skinny Jeans', 'Knit Sweater', 'Chino Pants'],
            'Zara' => ['Floral Dress', 'Blazer', 'Leather Boots', 'Evening Gown', 'Overcoat'],
            'Levi\'s' => ['501 Original Jeans', 'Trucker Jacket', 'Western Shirt', 'Denim Shorts'],
            // Fallback for other brands
            'default' => ['T-Shirt', 'Jeans', 'Hoodie', 'Sweatpants', 'Sneakers', 'Jacket', 'Accessory']
        ];

        $brands     = ['Zara', 'H&M', 'Nike', 'Adidas', 'Mango', "Levi's", 'Uniqlo',
                       'Pull&Bear', 'Reserved', 'C&A', 'Tommy Hilfiger', 'Calvin Klein'];
        $sizes      = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'One Size'];
        $conditions = ['new', 'like_new', 'good', 'fair'];
        $statuses   = ['active', 'active', 'active', 'sold', 'hidden']; // weighted toward active

        $total     = 10000;
        $batchSize = 500;

        for ($start = 0; $start < $total; $start += $batchSize) {
            $batch = [];
            $count = min($batchSize, $total - $start);

            for ($i = 0; $i < $count; $i++) {
                $brand = $faker->randomElement($brands);
                $randomId = rand(1, 1000);
                $imageUrl = "https://loremflickr.com/400/400/clothing," . strtolower($brand) . "/all?lock=" . $randomId;
                $possibleItems = $itemTypes[$brand] ?? $itemTypes['default'];
                $itemName = $faker->randomElement($possibleItems);
    
                $adj = ($faker->boolean(50)) ? $faker->randomElement($adjectives) . ' ' : '';
    
                $batch[] = [
                    'user_id'     => $faker->randomElement($userIds),
                    'category_id' => $faker->randomElement($categoryIds),
                    'title'       => $adj . $brand . ' ' . $itemName,
                    'description' => $faker->paragraph(3),
                    'price'       => $faker->randomFloat(2, 1, 150),
                    'size'        => $faker->randomElement($sizes),
                    'condition'   => $faker->randomElement($conditions),
                    'brand'       => $brand,
                    'image'       => $imageUrl,
                    'status'      => $faker->randomElement($statuses),
                    'views'       => $faker->numberBetween(0, 500),
                    'created_at'  => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
            }

            $this->db->table('items')->insertBatch($batch);
            echo 'Inserted: ' . ($start + $count) . " / {$total}\n";
        }
    }
}