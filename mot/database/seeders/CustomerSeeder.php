<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Wishlist;
use App\Service\UserService;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create([
            'name' => 'Najam Haq',
            'email' => 'customer@mot.com',
            'password' => \Hash::make('123456789')
        ]);
        Wishlist::factory()->count(5)->create(['customer_id' => $customer->id]);
        CustomerAddress::factory()->count(5)->create(['customer_id' => $customer->id]);
    }
}
