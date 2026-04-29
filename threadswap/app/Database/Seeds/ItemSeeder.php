<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ItemSeeder extends Seeder
{
    private array $categoryKeywords = [
        'womens-tops'    => 'womens+blouse+top+fashion',
        'womens-bottoms' => 'womens+jeans+skirt+fashion',
        'mens-tops'      => 'mens+shirt+tshirt+fashion',
        'mens-bottoms'   => 'mens+trousers+jeans+fashion',
        'dresses-skirts' => 'dress+skirt+fashion+clothing',
        'jackets-coats'  => 'jacket+coat+outerwear+fashion',
        'shoes'          => 'shoes+sneakers+boots+footwear',
        'accessories'    => 'handbag+accessories+fashion',
        'sportswear'     => 'sportswear+activewear+gym+clothing',
        'kids'           => 'kids+childrens+clothing+fashion',
        'default'        => 'clothing+fashion+apparel',
    ];

    private array $itemTypes = [
        'Nike'           => ['Air Force 1', 'Jordan 1 Low', 'Running Jacket', 'Sport Hoodie', 'Tech Fleece Joggers', 'Training Shorts', 'Dri-FIT T-Shirt', 'Wind Runner Jacket'],
        'Adidas'         => ['Superstar Sneakers', 'Ultraboost Runners', 'Tracksuit Top', 'Originals Hoodie', 'Gazelle Trainers', 'Classic T-Shirt', 'Tiro Track Pants'],
        'H&M'            => ['Basic Cotton Tee', 'Denim Jacket', 'Skinny Jeans', 'Knit Sweater', 'Chino Trousers', 'Linen Shirt', 'Oversized Hoodie'],
        'Zara'           => ['Floral Midi Dress', 'Tailored Blazer', 'Leather Ankle Boots', 'Wrap Blouse', 'Wide-Leg Trousers', 'Trench Coat', 'Knitted Cardigan'],
        "Levi's"         => ['501 Original Jeans', 'Trucker Denim Jacket', 'Western Shirt', 'Slim Taper Jeans', 'Ribcage Straight Jeans', 'Sherpa Jacket'],
        'Mango'          => ['Satin Slip Dress', 'Structured Blazer', 'High-Waist Jeans', 'Linen Trousers', 'Printed Blouse', 'Wool Coat'],
        'Uniqlo'         => ['Heattech Long-Sleeve', 'Ultra Light Down Jacket', 'Merino Wool Sweater', 'Oxford Shirt', 'Smart Ankle Trousers', 'Puffer Vest'],
        'Pull&Bear'      => ['Graphic Print Tee', 'Cargo Trousers', 'Denim Shorts', 'Zip-Up Hoodie', 'Relaxed Fit Jeans', 'Padded Jacket'],
        'Reserved'       => ['Midi Wrap Dress', 'Tailored Coat', 'Slim Fit Trousers', 'Floral Blouse', 'Ribbed Turtleneck', 'Puffer Coat'],
        'C&A'            => ['Classic Polo Shirt', 'Regular Fit Jeans', 'Fleece Jacket', 'V-Neck Jumper', 'Cargo Shorts', 'Trench Coat'],
        'Tommy Hilfiger' => ['Polo Shirt', 'Slim Chinos', 'Logo Hoodie', 'Varsity Jacket', 'Classic Denim Shirt', 'Cable-Knit Sweater'],
        'Calvin Klein'   => ['Slim Jeans', 'Monogram T-Shirt', 'Satin Slip Skirt', 'Logo Hoodie', 'Bralette Top', 'Relaxed Blazer'],
    ];

    private array $adjectives = ['Vintage', 'Casual', 'Classic', 'Slim Fit', 'Oversized', 'Premium', 'Lightweight', 'Relaxed', 'Cropped', 'Graphic'];
    private array $sizes      = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'One Size'];
    private array $conditions = ['new', 'like_new', 'good', 'fair'];
    private array $statuses   = ['active', 'active', 'active', 'active', 'sold', 'hidden'];

    public function run(): void
    {
        $faker = Factory::create();

        $userIds = array_column(
            $this->db->table('users')->where('role', 'user')->get()->getResultArray(),
            'id'
        );

        $categories      = $this->db->table('categories')->get()->getResultArray();
        $categoryIds     = array_column($categories, 'id');
        $categorySlugMap = array_column($categories, 'slug', 'id');

        if (empty($userIds) || empty($categoryIds)) {
            echo "ERROR: Run AdminSeeder and UserSeeder first!\n";
            return;
        }

        $brands    = array_keys($this->itemTypes);
        $total     = 10000;
        $batchSize = 500;

        for ($start = 0; $start < $total; $start += $batchSize) {
            $batch = [];
            $count = min($batchSize, $total - $start);

            for ($i = 0; $i < $count; $i++) {
                $brand      = $faker->randomElement($brands);
                $categoryId = $faker->randomElement($categoryIds);
                $slug       = $categorySlugMap[$categoryId] ?? 'default';
                $keywords   = $this->categoryKeywords[$slug] ?? $this->categoryKeywords['default'];

                // Each item gets a unique &sig so Unsplash rotates photos
                // while keeping them on-topic for the category
                $sig      = $start + $i;
                $imageUrl = "https://source.unsplash.com/400x400/?{$keywords}&sig={$sig}";

                $itemName = $faker->randomElement($this->itemTypes[$brand]);
                $adj      = $faker->boolean(35) ? $faker->randomElement($this->adjectives) . ' ' : '';
                $title    = trim($adj . $brand . ' ' . $itemName);

                $batch[] = [
                    'user_id'     => $faker->randomElement($userIds),
                    'category_id' => $categoryId,
                    'title'       => $title,
                    'description' => $this->makeDescription($faker, $brand, $itemName),
                    'price'       => $faker->randomFloat(2, 3, 150),
                    'size'        => $faker->randomElement($this->sizes),
                    'condition'   => $faker->randomElement($this->conditions),
                    'brand'       => $brand,
                    'image'       => $imageUrl,
                    'status'      => $faker->randomElement($this->statuses),
                    'views'       => $faker->numberBetween(0, 500),
                    'created_at'  => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
            }

            $this->db->table('items')->insertBatch($batch);
            echo 'Inserted: ' . ($start + $count) . " / {$total}\n";
        }

        echo "Done! {$total} items seeded.\n";
    }

    private function makeDescription(\Faker\Generator $faker, string $brand, string $itemName): string
    {
        $intros = [
            "Selling my {$brand} {$itemName} — no longer fits.",
            "Up for sale: {$brand} {$itemName}, barely worn.",
            "Pre-loved {$brand} {$itemName} looking for a new home.",
            "{$brand} {$itemName} — clearing out my wardrobe.",
            "Lovely {$brand} {$itemName}, worn only a couple of times.",
        ];
        $conds = [
            'Excellent condition — no signs of wear.',
            'Very good condition, washed and ready to go.',
            'Good used condition with minor signs of wear.',
            'Some light fading but otherwise great.',
            'Well looked after throughout.',
        ];
        $closes = [
            'Happy to post — buyer covers shipping costs.',
            'Can meet locally or post at buyer\'s expense.',
            'Cash on collection preferred, postage possible.',
            'Open to sensible offers.',
            'No trades please.',
        ];

        return $faker->randomElement($intros) . ' '
             . $faker->randomElement($conds) . ' '
             . $faker->randomElement($closes);
    }
}