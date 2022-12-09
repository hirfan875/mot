<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreOrder;
use App\Models\Store;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = Customer::where('email', 'customer@mot.com')->first();
        Order::factory()->count(5)->create();
        Order::factory()->count(5)->create(['status'=> Order::CANCELLED_ID, 'customer_id' => $customer->id])->each(function(Order $order){
            $store = Store::first();
            /** @var Product $product */
            $product = Product::factory()->create(['store_id' => $store->id]);
            /** @var StoreOrder $sellerOrdestoreOrder */
            $storeOrder = StoreOrder::factory()->create(['order_id'=> $order->id, 'store_id' => $store->id]);
            OrderItem::factory()->create([
                'product_id' => $product->id,
                'store_order_id'=> $storeOrder->id
            ]);
        });
        Order::factory()->count(15)->create(['customer_id' => $customer->id])->each(function(Order $order){
            $store = Store::first();
            /** @var Product $product */
            $product = Product::factory()->create(['store_id' => $store->id]);
            /** @var StoreOrder $sellerOrdestoreOrder */
            $storeOrder = StoreOrder::factory()->create(['order_id'=> $order->id, 'store_id' => $store->id]);
            OrderItem::factory()->create([
                'product_id' => $product->id,
                'store_order_id'=> $storeOrder->id
            ]);
        });
    }
}
