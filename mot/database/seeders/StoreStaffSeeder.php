<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreStaff;

class StoreStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoreStaff::factory()->count(5)->create();
    }
}
