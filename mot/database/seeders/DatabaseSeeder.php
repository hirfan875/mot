<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            MovePlaceholderToStorageFolder::class,
            TagSeeder::class,
            UserSeeder::class,
            LanguageSeeder::class,
            CurrencySeeder::class,
            CountrySeeder::class,
            StoreSeeder::class,         // expect to create 4 products
            BrandSeeder::class,
            AttributeSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,       // creating 131 products
            DailyDealsSeeder::class,
            FlashDealsSeeder::class,
            SliderSeeder::class,
            CouponSeeder::class,
            CustomerSeeder::class,      // 5 products for wishlist
            OrderSeeder::class,         // expect 20 products created here
            HomePageSectionsSeeder::class,
            ReturnRequestSeeder::class,
            ProductBannerSeeder::class
        ]);
    }
}
