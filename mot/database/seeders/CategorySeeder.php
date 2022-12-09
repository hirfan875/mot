<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = [
            'Home & Garden',
            'Jewellery & Watches',
            'Phones & Accessories',
            'Consumer Electronics',
            'Tools & Home',
            'Automobiles',
            'Beauty & Health',
            'Baby & Kids',
            'Sports & Entertainment',
            'Women\'s Clothing',
            'Toys & Hobbies',
            'Computer & Office',
            'Men\'s Clothing'
        ];

        foreach ($titles as $title) {
            Category::factory()->has(Category::factory()->count(2))->create(['title' => $title]);
        }
    }
}
