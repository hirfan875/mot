<?php

namespace Database\Seeders;

use App\Models\StoreReview;
use Illuminate\Database\Seeder;
use App\Models\StoreStaff;
use App\Models\Store;
use App\Models\StoreData;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::factory()->has(StoreData::factory()->count(1), 'store_data')->create()->each(function(Store $store){
            StoreReview::factory()->count(4)->create([
                'store_id' =>$store->id
            ]);
            StoreStaff::factory()->create([
                'email' => 'seller@mot.com',
                'is_owner' => true,
                'store_id' => $store->id,
                'password' => Hash::make('password')
            ]);
        });
        Store::factory()->has(StoreData::factory()->count(1), 'store_data')->count(4)->create()->each(function(Store $store){
            StoreStaff::factory()->create([
                'is_owner'=>true,
                'store_id' => $store->id]);
        });
    }
}
